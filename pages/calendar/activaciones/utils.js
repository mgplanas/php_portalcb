/***************************************************************************************
 * Verifica si una fecha es Feriado o Día no laborable
 * @param Date inicio - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const elRangoEstaEnHorarioLaboral = (inicio, fin, eventos) => {

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
}