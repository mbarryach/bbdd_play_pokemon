-- ═══════════════════════════════════════════════════════════════
--  VISTAS SQL — SISTEMA TORNEOS POKÉMON TCG
-- ═══════════════════════════════════════════════════════════════

USE torneo_db;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 1: CLASIFICACIÓN SUIZA
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_clasificacion AS
SELECT
    t.ID_Torneo                              AS torneo_id,
    t.Nombre                                 AS torneo,
    temp.Anio                                AS temporada,

    j.ID_Jugador                             AS jugador_id,
    CONCAT(j.Nombre, ' ', j.Apellidos)       AS jugador,
    j.Player_ID,
    j.Division,

    cs.Partidos_Jugados                      AS partidas_jugadas,
    cs.Victorias                             AS victorias,
    cs.Derrotas                              AS derrotas,
    cs.Empates                               AS empates,

    cs.Puntos_Totales                        AS puntos,

    cs.OMW_Percentage                        AS omw_percentage,
    cs.PMW_Percentage                        AS pmw_percentage,
    cs.OOM_Percentage                        AS oom_percentage,

    cs.Posicion_Final                        AS posicion_final

FROM CLASIFICACION_SUIZA cs

JOIN JUGADOR j
    ON j.ID_Jugador = cs.ID_Jugador

JOIN TORNEO t
    ON t.ID_Torneo = cs.ID_Torneo

LEFT JOIN TEMPORADA temp
    ON temp.ID_Temporada = t.ID_Temporada

ORDER BY
    t.ID_Torneo,
    cs.Posicion_Final ASC,
    cs.Puntos_Totales DESC;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 2: RESULTADOS DE PARTIDOS
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_resultados AS
SELECT
    rp.ID_Resultado                          AS resultado_id,

    e.ID_Emparejamiento                      AS emparejamiento_id,

    t.ID_Torneo                              AS torneo_id,
    t.Nombre                                 AS torneo,

    e.Numero_Ronda                           AS ronda,
    e.Fase                                   AS fase,
    e.Num_Mesa                               AS mesa,

    e.Hora_Programada,

    j1.ID_Jugador                            AS jugador1_id,
    CONCAT(j1.Nombre, ' ', j1.Apellidos)     AS jugador1,
    j1.Player_ID                             AS player1_id,

    rp.Juegos_Jugador1,

    j2.ID_Jugador                            AS jugador2_id,
    CONCAT(j2.Nombre, ' ', j2.Apellidos)     AS jugador2,
    j2.Player_ID                             AS player2_id,

    rp.Juegos_Jugador2,

    CASE
        WHEN rp.Empate = TRUE THEN 'Empate'
        WHEN rp.ID_Ganador = j1.ID_Jugador
            THEN CONCAT(j1.Nombre, ' ', j1.Apellidos)
        ELSE CONCAT(j2.Nombre, ' ', j2.Apellidos)
    END AS ganador,

    rp.Verificado,
    rp.Hora_Finalizacion

FROM RESULTADO_PARTIDO rp

JOIN EMPAREJAMIENTO e
    ON e.ID_Emparejamiento = rp.ID_Emparejamiento

JOIN TORNEO t
    ON t.ID_Torneo = e.ID_Torneo

JOIN JUGADOR j1
    ON j1.ID_Jugador = e.ID_Jugador1

JOIN JUGADOR j2
    ON j2.ID_Jugador = e.ID_Jugador2

ORDER BY rp.Hora_Finalizacion DESC;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 3: PRÓXIMAS PARTIDAS
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_proximas_partidas AS
SELECT
    e.ID_Emparejamiento                      AS emparejamiento_id,

    t.ID_Torneo                              AS torneo_id,
    t.Nombre                                 AS torneo,

    e.Numero_Ronda                           AS ronda,
    e.Fase                                   AS fase,
    e.Num_Mesa                               AS mesa,

    e.Hora_Programada,

    j1.ID_Jugador                            AS jugador1_id,
    CONCAT(j1.Nombre, ' ', j1.Apellidos)     AS jugador1,

    j2.ID_Jugador                            AS jugador2_id,
    CONCAT(j2.Nombre, ' ', j2.Apellidos)     AS jugador2

FROM EMPAREJAMIENTO e

JOIN TORNEO t
    ON t.ID_Torneo = e.ID_Torneo

JOIN JUGADOR j1
    ON j1.ID_Jugador = e.ID_Jugador1

JOIN JUGADOR j2
    ON j2.ID_Jugador = e.ID_Jugador2

WHERE NOT EXISTS (
    SELECT 1
    FROM RESULTADO_PARTIDO rp
    WHERE rp.ID_Emparejamiento = e.ID_Emparejamiento
)

ORDER BY e.Hora_Programada ASC;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 4: JUGADORES
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_jugadores AS
SELECT
    j.ID_Jugador,

    j.Nombre,
    j.Apellidos,

    CONCAT(j.Nombre, ' ', j.Apellidos) AS nombre_completo,

    j.Player_ID,
    j.Email,
    j.Pais,
    j.Division,

    j.CP_Totales,
    j.CP_Temporada_Actual,

    temp.Anio AS temporada_actual,

    COUNT(DISTINCT i.ID_Torneo) AS torneos_jugados

FROM JUGADOR j

LEFT JOIN TEMPORADA temp
    ON temp.ID_Temporada = j.ID_Temporada_Actual

LEFT JOIN INSCRIPCION i
    ON i.ID_Jugador = j.ID_Jugador

GROUP BY
    j.ID_Jugador,
    j.Nombre,
    j.Apellidos,
    j.Player_ID,
    j.Email,
    j.Pais,
    j.Division,
    j.CP_Totales,
    j.CP_Temporada_Actual,
    temp.Anio

ORDER BY j.Apellidos, j.Nombre;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 5: TORNEOS
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_torneos AS
SELECT
    t.ID_Torneo,

    t.Nombre,
    t.Tipo_Torneo,

    t.Fecha_Inicio,
    t.Fecha_Fin,

    t.Ubicacion,
    t.Pais,

    t.Num_Rondas_Suizas,
    t.Tamanio_Top_Cut,

    temp.Anio AS temporada,

    COUNT(DISTINCT i.ID_Jugador)          AS total_inscritos,

    COUNT(DISTINCT e.ID_Emparejamiento)   AS total_partidas,

    COUNT(DISTINCT rp.ID_Resultado)       AS partidas_jugadas,

    CASE
        WHEN CURDATE() < t.Fecha_Inicio THEN 'Pendiente'
        WHEN CURDATE() BETWEEN t.Fecha_Inicio AND t.Fecha_Fin THEN 'En curso'
        ELSE 'Finalizado'
    END AS estado

FROM TORNEO t

LEFT JOIN TEMPORADA temp
    ON temp.ID_Temporada = t.ID_Temporada

LEFT JOIN INSCRIPCION i
    ON i.ID_Torneo = t.ID_Torneo

LEFT JOIN EMPAREJAMIENTO e
    ON e.ID_Torneo = t.ID_Torneo

LEFT JOIN RESULTADO_PARTIDO rp
    ON rp.ID_Emparejamiento = e.ID_Emparejamiento

GROUP BY
    t.ID_Torneo,
    t.Nombre,
    t.Tipo_Torneo,
    t.Fecha_Inicio,
    t.Fecha_Fin,
    t.Ubicacion,
    t.Pais,
    t.Num_Rondas_Suizas,
    t.Tamanio_Top_Cut,
    temp.Anio

ORDER BY t.Fecha_Inicio DESC;

-- ═══════════════════════════════════════════════════════════════
-- VISTA 6: LISTAS DE MAZO
-- ═══════════════════════════════════════════════════════════════

CREATE OR REPLACE VIEW v_listas_mazos AS
SELECT
    lm.ID_Lista,
    lm.Nombre_Mazo,

    j.ID_Jugador,
    CONCAT(j.Nombre, ' ', j.Apellidos) AS jugador,

    t.ID_Torneo,
    t.Nombre AS torneo,

    lm.Verificada,
    lm.Fecha_Entrega,

    COUNT(cem.ID_Carta) AS total_cartas

FROM LISTA_MAZO lm

JOIN INSCRIPCION i
    ON i.ID_Inscripcion = lm.ID_Inscripcion

JOIN JUGADOR j
    ON j.ID_Jugador = i.ID_Jugador

JOIN TORNEO t
    ON t.ID_Torneo = i.ID_Torneo

LEFT JOIN CARTA_EN_MAZO cem
    ON cem.ID_Lista = lm.ID_Lista

GROUP BY
    lm.ID_Lista,
    lm.Nombre_Mazo,
    j.ID_Jugador,
    j.Nombre,
    j.Apellidos,
    t.ID_Torneo,
    t.Nombre,
    lm.Verificada,
    lm.Fecha_Entrega;

-- ═══════════════════════════════════════════════════════════════
-- ÍNDICES RECOMENDADOS
-- ═══════════════════════════════════════════════════════════════

CREATE INDEX idx_inscripcion_jugador
ON INSCRIPCION(ID_Jugador);

CREATE INDEX idx_inscripcion_torneo
ON INSCRIPCION(ID_Torneo);

CREATE INDEX idx_emparejamiento_torneo
ON EMPAREJAMIENTO(ID_Torneo);

CREATE INDEX idx_emparejamiento_jugador1
ON EMPAREJAMIENTO(ID_Jugador1);

CREATE INDEX idx_emparejamiento_jugador2
ON EMPAREJAMIENTO(ID_Jugador2);

CREATE INDEX idx_resultado_emparejamiento
ON RESULTADO_PARTIDO(ID_Emparejamiento);

CREATE INDEX idx_resultado_ganador
ON RESULTADO_PARTIDO(ID_Ganador);

CREATE INDEX idx_clasificacion_torneo
ON CLASIFICACION_SUIZA(ID_Torneo);

CREATE INDEX idx_clasificacion_jugador
ON CLASIFICACION_SUIZA(ID_Jugador);

-- ═══════════════════════════════════════════════════════════════
-- COMPROBACIÓN FINAL
-- ═══════════════════════════════════════════════════════════════

SHOW FULL TABLES IN torneo_db
WHERE TABLE_TYPE = 'VIEW';