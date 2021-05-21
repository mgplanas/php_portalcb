/***************************************************************************************
 * Muestra u Oculta el contenedor del calendario
 * @author MVGP
 ****************************************************************************************/
const toggleCalendar = () => {
    let calContainer = $('#calendar_container');
    if (calContainer.hasClass('in')) {
        $('#calendar_container').collapse('hide');
    } else {
        $('#calendar_container').collapse('show');
    }
}

/***************************************************************************************
 * Cambia la vista del calendario entre por período de 11 al 10 o por mes
 * @param {Fullcalendar} cal - Instancia del calendario
 * @param {string} viewname - Nombre de la vista
 * @author MVGP
 ****************************************************************************************/
const toogleView = (cal, viewName) => {
    cal.changeView(viewName);
    if (viewName === 'monthview') {
        $('#cal-nav-btn-prev').attr('title', 'mes previo');
        $('#cal-nav-btn-next').attr('title', 'mes siguiente');
    } else {
        $('#cal-nav-btn-prev').attr('title', 'período previo');
        $('#cal-nav-btn-next').attr('title', 'período siguiente');
    }
    updateNavTitle(cal.view.title);

}


const incrementDate = (cal, increment) => {
    cal.incrementDate({ months: increment });
    updateNavTitle(cal.view.title);
}

const updateNavTitle = (title) => {
    $('#cal-nav-title').html(title);
}

const init = (cal) => {
    $('#cal-nav-btn-periodo').on('click', () => toogleView(cal, 'byPeriod'));
    $('#cal-nav-btn-mes').on('click', () => toogleView(cal, 'monthview'));
    $('#cal-nav-btn-prev').on('click', () => incrementDate(cal, -1));
    $('#cal-nav-btn-next').on('click', () => incrementDate(cal, 1));
    $('#cal-nav-btn-toggle').on('click', toggleCalendar);

    $('#calendar_container').on('shown.bs.collapse', () => cal.render());

    toogleView(cal, 'monthview');


}

export { init }