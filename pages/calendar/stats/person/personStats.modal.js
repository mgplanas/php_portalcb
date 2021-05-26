import * as utils from '../../utils.js'

let gx_acum_x_mes = null;
// AM Por Responsables
const gxAcumuladoMensual = (id_persona, m_inicio, m_fin, tipo) => {

    const mLastYear = moment(m_inicio).subtract(1, 'years');
    const mLastMonth = moment(m_inicio).subtract(1, 'months');
    if (gx_acum_x_mes != null) {
        gx_acum_x_mes.destroy();
    }

    let gxData = [];
    let name = [];
    let suma = [];
    // Si elije todas filtro por año directamente
    utils.cantidadMinAcumuladosPeriodoByPerson(id_persona, mLastYear.format('YYYY-MM-01'), mLastMonth.endOf('month').format('YYYY-MM-DD'), tipo)
        .then(res => {
            gxData = res
            gxData.forEach(e => {
                name.push(`${e.year}-${e.mes}`);
                suma.push(e.suma / 60);
            });
            let chartdata = {
                labels: name,
                datasets: [{
                    label: 'Acumulado',
                    data: suma,
                    backgroundColor: 'rgb(243, 156, 18)'
                }]
            };
            let options = {
                responsive: true,
                title: {
                    display: true,
                    position: "top",
                    text: "Acum. mensual [hs]",
                    fontSize: 18,
                    fontColor: "#111"
                },
                legend: {
                    display: false,
                    position: "top",
                    labels: {
                        fontColor: "#333",
                        fontSize: 16
                    }
                },
                scales: {
                    xAxes: [{ stacked: true }],
                    yAxes: [{ stacked: true }]
                }
            };

            let graphTarget = $("#gx_acumulado_mensual");

            gx_acum_x_mes = new Chart(graphTarget, {
                type: 'horizontalBar',
                data: chartdata,
                options: options
            });
        })
        .catch(err => console.log(err));
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const reset = () => {
    $('#modal-cal-per-stat-id').val(0);
    $('#modal-cal-per-stat-hs-per').html(0);
    $('#modal-cal-per-stat-hs-container').removeClass('bg-green bg-yellow bg-red');
}

/***************************************************************************************
 * agregar Registro de Hora 
 * @author MVGP
 ****************************************************************************************/
const showStatsForPerson = (resource, cal, events) => {
    let eventos = resource.getEvents();
    const m_inicio = moment(cal.view.currentStart);
    const m_fin = moment(cal.view.currentEnd);


    $('#modal-cal-per-stat-title').html(`Estadísticas para ${resource.title}`);
    reset();

    // Acumulado mes actual
    const eventosHsNORechazados = eventos
        .filter(event => event.extendedProps.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS)
        .filter(event => event.extendedProps.estado != utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO);
    const hs = utils.cantidadMinAcumulados(eventosHsNORechazados, utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS) / 60;
    $('#modal-cal-per-stat-hs-per').html(hs);

    // Acumulado horas año corriente
    $('#modal-cal-per-stat-hs-anual').html('N/D');
    utils.cantidadMinAcumuladosPeriodoByPerson(resource.id, m_inicio.format('YYYY-01-01'), m_inicio.format('YYYY-12-31'), utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS)
        .then(res => $('#modal-cal-per-stat-hs-anual').html(res[0].suma / 60))
        .catch(err => console.log(err));

    // Grafico Barras mes a mes 1-y
    gxAcumuladoMensual(resource.id, m_inicio, m_inicio, utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS);
    $("#modal-cal-per-stat").modal("show");
}


export { showStatsForPerson }