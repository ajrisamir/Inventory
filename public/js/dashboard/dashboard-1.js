(function($) {
    "use strict"

    // Chart Widget 4
    try {
        var ctx = document.getElementById("chart_widget_4");
        if (ctx) {
            ctx.height = 70;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
                    datasets: [{
                        data: [0, 15, 57, 12, 85, 10],
                        label: "Penjualan",
                        backgroundColor: '#847DFA',
                        borderColor: '#847DFA',
                        borderWidth: 0.5,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: '#847DFA',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            display: false
                        }],
                        yAxes: [{
                            display: false
                        }]
                    }
                }
            });
        }
    } catch (error) {
        console.log("Inisialisasi grafik dilewati:", error);
    }

})(jQuery);