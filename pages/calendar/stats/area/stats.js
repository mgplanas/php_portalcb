import * as utils from '../../utils.js'

let gx_top_10 = null;

const gxTopTenHsByEventsUpdate = events => {
        const eventosRegistrosHs = events.filter(e => e.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS &&
            e.estado != utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO);
        gxTopTenHsByEvents(eventosRegistrosHs);
    }
    // AM Por Responsables
const gxTopTenHsByEvents = eventos => {

    if (gx_top_10 != null) {
        gx_top_10.destroy();
    }

    let res = {};
    res = eventos.forEach(e => {
        const mInicio = moment(e.fecha_inicio);
        const mFin = moment(e.fecha_fin);
        if (res.hasOwnProperty(e.fullname)) {
            res[e.fullname] += moment.duration(mFin.diff(mInicio)).asMinutes() / 60;
        } else {
            res[e.fullname] = moment.duration(mFin.diff(mInicio)).asMinutes() / 60;;
        }
    });
    //.sort((a, b) => a.suma - b.suma);
    let data = [];
    let name = [];
    let suma = [];
    for (let total in res) {
        data.push([total, res[total]]);
    }
    data.sort((a, b) => a[1] - b[1]);
    data.forEach(a => {
        name.push(a[0]);
        suma.push(a[1]);
    })




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

    let graphTarget = $("#gx_top_10_hs");

    gx_top_10 = new Chart(graphTarget, {
        type: 'horizontalBar',
        data: chartdata,
        options: options
    });
}

export { gxTopTenHsByEventsUpdate }