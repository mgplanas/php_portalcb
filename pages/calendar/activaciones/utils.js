/***************************************************************************************
 * Constantes
 ****************************************************************************************/
const RULE_RANGO_LABORAL_INICIO = '09:00:00';
const RULE_RANGO_LABORAL_FIN = '17:30:00';


/***************************************************************************************
 * Verifica un evento está solapdado con otro
 * @param Moment a_inicio - incio del primer evento
 * @param Moment a_fin - fin del primer evento
 * @param Moment b_inicio - incio del segundo evento
 * @param Moment b_fin - fin del segundo evento
 * @author MVGP
 ****************************************************************************************/
const estanSolapados = (a_inicio, a_fin, b_inicio, b_fin) => {
    return (
        a_inicio.isBetween(b_inicio, b_fin, 'minutes', "()") ||
        a_fin.isBetween(b_inicio, b_fin, 'minutes', "()")
    );
}

/***************************************************************************************
 * Verifica si una fecha es Feriado o Día no laborable
 * @param Moment fecha - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaHorarioLaboral = (fecha, eventos) => {

    if (esWeekEnd(fecha)) return false;

    if (esDNL(fecha, eventos)) return false;

    // formo el rango inicio fin para la fecha a verificar
    const comienzo_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_RANGO_LABORAL_INICIO}`);
    const fin_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_RANGO_LABORAL_FIN}`);
    return (fecha.isBetween(comienzo_jornada, fin_jornada, 'minutes', "()"));
}

/***************************************************************************************
 * Verifica si un rango se spolapa con horario laboral
 * @param Moment inicio - Fecha a verificar en Moment
 * @param Moment fin - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaRangoConHorarioLaboral = (inicio, fin, eventos) => {
    return (solapaHorarioLaboral(inicio, eventos) || solapaHorarioLaboral(fin, eventos));
}

/***************************************************************************************
 * Verifica si una fecha es Feriado o Día no laborable
 * @param Date inicio - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const esDNL = (m_fecha, eventos) => {
    const feriadosQueAplican = eventos
        .filter(e => e.extendedProps.tipo == 1) // Feriados DNL
        .filter(el => {
            const m_inicio = moment(el.start);
            const res = (m_fecha.format('YYYY-MM-DD') == m_inicio.format('YYYY-MM-DD'));
            return res;
        });

    return feriadosQueAplican.length > 0;

}

/***************************************************************************************
 * Verifica si una fecha es Fin de Semana
 * @param Date inicio - Fecha a verificar en Moment
 * @author MVGP
 ****************************************************************************************/
const esWeekEnd = (m_fecha) => {
    return (m_fecha.isoWeekday() >= 6);
}

/***************************************************************************************
 * Verifica si posee esquema de guardias
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const estaEnEsquemaDeGuardia = (eventos) => {
    return eventos.filter(ev => ev.extendedProps.tipo == 4).length > 0;
}




export {
    esDNL,
    esWeekEnd,
    estaEnEsquemaDeGuardia,
    estanSolapados,
    solapaRangoConHorarioLaboral,
    solapaHorarioLaboral
}