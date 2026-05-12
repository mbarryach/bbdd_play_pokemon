-- ═══════════════════════════════════════════════════════════
--  vistas_sql.sql
--  Ejecutar en MySQL Workbench sobre torneo_db
--
--  POR QUÉ USAR VISTAS:
--  - Evitan repetir JOINs complejos en cada consulta PHP.
--  - Los modelos MVC hacen SELECT simple sobre la vista.
--  - Si cambia la estructura de tablas, solo se actualiza
--    la vista, no todos los modelos.
--  - Mejoran la legibilidad del código PHP.
-- ═══════════════════════════════════════════════════════════

USE torneo_db;

-- ─────────────────────────────────────────────────────────
--  VISTA 1: v_clasificacion
--
--  Calcula la tabla de clasificación de cada equipo:
--  partidos jugados, ganados, empatados, perdidos,
--  goles a favor/en contra, diferencia y puntos.
--  Ordenada por puntos desc, luego diferencia de goles.
-- ─────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW v_clasificacion AS
SELECT
    e.id                                                        AS equipo_id,
    e.nombre                                                    AS equipo,
    COUNT(p.id)                                                 AS pj,   -- partidos jugados
    SUM(
        CASE
            WHEN p.equipo_local_id = e.id AND p.goles_local > p.goles_visitante  THEN 1
            WHEN p.equipo_visitante_id = e.id AND p.goles_visitante > p.goles_local THEN 1
            ELSE 0
        END
    )                                                           AS pg,   -- ganados
    SUM(
        CASE
            WHEN p.goles_local = p.goles_visitante              THEN 1
            ELSE 0
        END
    )                                                           AS pe,   -- empatados
    SUM(
        CASE
            WHEN p.equipo_local_id = e.id AND p.goles_local < p.goles_visitante  THEN 1
            WHEN p.equipo_visitante_id = e.id AND p.goles_visitante < p.goles_local THEN 1
            ELSE 0
        END
    )                                                           AS pp,   -- perdidos
    SUM(
        CASE
            WHEN p.equipo_local_id    = e.id THEN p.goles_local
            WHEN p.equipo_visitante_id = e.id THEN p.goles_visitante
            ELSE 0
        END
    )                                                           AS gf,   -- goles a favor
    SUM(
        CASE
            WHEN p.equipo_local_id    = e.id THEN p.goles_visitante
            WHEN p.equipo_visitante_id = e.id THEN p.goles_local
            ELSE 0
        END
    )                                                           AS gc,   -- goles en contra
    SUM(
        CASE
            WHEN p.equipo_local_id    = e.id THEN p.goles_local    - p.goles_visitante
            WHEN p.equipo_visitante_id = e.id THEN p.goles_visitante - p.goles_local
            ELSE 0
        END
    )                                                           AS dg,   -- diferencia
    SUM(
        CASE
            WHEN p.equipo_local_id = e.id AND p.goles_local > p.goles_visitante  THEN 3
            WHEN p.equipo_visitante_id = e.id AND p.goles_visitante > p.goles_local THEN 3
            WHEN p.goles_local = p.goles_visitante                                THEN 1
            ELSE 0
        END
    )                                                           AS pts   -- puntos
FROM equipos e
LEFT JOIN partidos p
       ON (p.equipo_local_id = e.id OR p.equipo_visitante_id = e.id)
      AND p.jugado = 1
GROUP BY e.id, e.nombre
ORDER BY pts DESC, dg DESC, gf DESC;


-- ─────────────────────────────────────────────────────────
--  VISTA 2: v_resultados
--
--  Lista todos los partidos jugados con nombres de equipo,
--  resultado y fecha. Lista el partido más reciente primero.
-- ─────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW v_resultados AS
SELECT
    p.id,
    el.nombre                               AS equipo_local,
    ev.nombre                               AS equipo_visitante,
    p.goles_local,
    p.goles_visitante,
    CONCAT(p.goles_local, ' — ', p.goles_visitante)  AS resultado,
    CASE
        WHEN p.goles_local > p.goles_visitante  THEN el.nombre
        WHEN p.goles_visitante > p.goles_local  THEN ev.nombre
        ELSE 'Empate'
    END                                     AS ganador,
    p.fecha,
    p.ronda
FROM  partidos  p
JOIN  equipos  el ON el.id = p.equipo_local_id
JOIN  equipos  ev ON ev.id = p.equipo_visitante_id
WHERE p.jugado = 1
ORDER BY p.fecha DESC;


-- ─────────────────────────────────────────────────────────
--  VISTA 3: v_proximos_partidos
--
--  Próximos partidos no jugados, ordenados por fecha.
-- ─────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW v_proximos_partidos AS
SELECT
    p.id,
    el.nombre   AS equipo_local,
    ev.nombre   AS equipo_visitante,
    p.fecha,
    p.ronda
FROM  partidos  p
JOIN  equipos  el ON el.id = p.equipo_local_id
JOIN  equipos  ev ON ev.id = p.equipo_visitante_id
WHERE p.jugado = 0
ORDER BY p.fecha ASC;


-- ─────────────────────────────────────────────────────────
--  VISTA 4: v_jugadores
--
--  Jugadores con nombre de su equipo incluido.
--  Evita el JOIN repetitivo en cada consulta del modelo.
-- ─────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW v_jugadores AS
SELECT
    j.id,
    j.nombre,
    j.apellidos,
    CONCAT(j.nombre, ' ', j.apellidos)  AS nombre_completo,
    j.apodo,
    j.email,
    j.activo,
    e.id     AS equipo_id,
    e.nombre AS equipo
FROM  jugadores j
LEFT JOIN equipos e ON e.id = j.equipo_id
ORDER BY e.nombre, j.apellidos, j.nombre;


-- ─────────────────────────────────────────────────────────
--  VISTA 5: v_estadisticas_equipos
--
--  Resumen rápido por equipo: partidos, goles, media.
--  Útil para el panel de administración.
-- ─────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW v_estadisticas_equipos AS
SELECT
    e.id,
    e.nombre,
    COUNT(DISTINCT j.id)    AS total_jugadores,
    COUNT(DISTINCT p.id)    AS partidos_jugados,
    COALESCE(SUM(
        CASE
            WHEN p.equipo_local_id    = e.id THEN p.goles_local
            WHEN p.equipo_visitante_id = e.id THEN p.goles_visitante
            ELSE 0
        END
    ), 0)                   AS goles_totales
FROM  equipos  e
LEFT JOIN jugadores j ON j.equipo_id = e.id
LEFT JOIN partidos  p ON (p.equipo_local_id = e.id OR p.equipo_visitante_id = e.id) AND p.jugado = 1
GROUP BY e.id, e.nombre;


-- ─────────────────────────────────────────────────────────
--  Verificar que las vistas se han creado correctamente
-- ─────────────────────────────────────────────────────────
SHOW FULL TABLES IN torneo_db WHERE TABLE_TYPE = 'VIEW';
