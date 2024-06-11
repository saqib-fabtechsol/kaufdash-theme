(function($) {
    $(function(){
            //add code here that needs to wait for page to be loaded
            //   document ready
            console.log('document ready');

            const ctx = document.getElementById('linechart').getContext('2d');
            const myLineChart = new Chart(ctx, {
                type: 'line',
                backgroundColor: 'rgba(255, 255, 255, 1)',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Store Performance',
                        data: [60, 65, 80, 81, 55 , 54 , 40],
                        backgroundColor: 'rgba(75, 192, 192, 0)',
                        borderColor: 'rgba(165, 32, 8, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        
                    }]
                },
                options: {
                    scales: {
                        y: {
                         
                            ticks: {
                                stepSize: 5 // Set the step size for y-axis ticks
                            }
                        }
                    }
                }
            });


            const ctx2 = document.getElementById('linechart-2').getContext('2d');
            const lineChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Total Orders',
                        data: [60, 65, 80, 81, 55 , 54 , 40],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(8, 165, 105, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            
                            ticks: {
                                stepSize: 5 // Set the step size for y-axis ticks
                            }

                        }
                    }
                }
            });

           
            
        
        });

    //and rest of code here
})(jQuery);