document.addEventListener("DOMContentLoaded", function () {

    // Initialisation pour "Revenus"
    var chartRev = new ApexCharts(document.querySelector("#chartRev"), optionsDay);
    chartRev.render();

    document.getElementById("dayBtnRev").addEventListener("click", function () {
        chartRev.updateOptions(optionsDay, true);
    });

    document.getElementById("weekBtnRev").addEventListener("click", function () {
        chartRev.updateOptions(optionsWeek, true);
    });

    document.getElementById("monthBtnRev").addEventListener("click", function () {
        chartRev.updateOptions(optionsMonth, true);
    });

    document.getElementById("yearBtnRev").addEventListener("click", function () {
        chartRev.updateOptions(optionsYear, true);
    });

    // Activation pour les boutons Revenus
    const btnRev = document.querySelectorAll(".chart-rev button");
    btnRev.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            btnRev.forEach((link) => link.classList.remove("active"));
            item.classList.add("active");
        });
    });

});