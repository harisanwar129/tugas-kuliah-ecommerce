<div class="card-body">
    <div class="chart-container">
        <div id="chart_2" class="amchart"></div>
    </div>
</div>

@prepend('js')
    <script>
        let chartTopSalesProductData;

        function loadPieTopSalesProduct(data = []) {
            var s = $('#chart_2').parents(".card");
            s.addClass("is-loading")
            $.ajax({
                url: "{{ route('v1.statistik.get_top_sales_product') }}",
                dataType: 'json',
                method: 'POST',
                data: data,
                success: function(response) {
                    s.removeClass("is-loading");
                    typeof chartTopSalesProductData === 'object' ? chartTopSalesProductData.dispose() : '';
                    var chartTopSalesProduct = am4core.create("chart_2", am4charts.PieChart3D);
                    let title = chartTopSalesProduct.titles.create();
                    title.text = "Top Sales Product";
                    title.fontSize = 18;
                    title.marginBottom = 10;

                    // Legend
                    chartTopSalesProduct.legend = new am4charts.Legend();
                    chartTopSalesProduct.legend.valueLabels.template.text = "{value.value}";



                    var data = response.map(el => {
                        return {
                            'name': el['name'],
                            'sales_count': el['total']
                        }
                    })

                    // Add data
                    chartTopSalesProduct.data = data;

                    // Add and configure Series
                    var pieSeries = chartTopSalesProduct.series.push(new am4charts.PieSeries3D());
                    pieSeries.dataFields.value = "sales_count";
                    pieSeries.dataFields.category = "name";
                    pieSeries.slices.template.stroke = am4core.color("#fff");
                    pieSeries.slices.template.strokeWidth = 2;
                    pieSeries.slices.template.strokeOpacity = 1;

                    pieSeries.colors.list = [
                        am4core.color("#845EC2"),
                        am4core.color("#FF6F91"),
                        am4core.color("#F9F871")
                    ];


                    // Color enable
                    pieSeries.slices.template.propertyFields.fill = "color";
                    pieSeries.labels.template.text = "{status}: {value.value}";

                    // This creates initial animation
                    pieSeries.hiddenState.properties.opacity = 1;
                    pieSeries.hiddenState.properties.endAngle = -90;
                    pieSeries.hiddenState.properties.startAngle = -90;

                    // Cursor
                    chartTopSalesProduct.padding(10, 10, 10, 10);

                    chartTopSalesProductData = chartTopSalesProduct;
                }
            })
        }

        $("#date_from_chart_2, #date_to_chart_2").on('change', function(e) {
            chartTopSalesProductData.dispose();

            let data = {
                'user_id': '{{ auth()->user()->id }}',
                'jumlah_produk': '{{ isset($jumlah_produk) ? $jumlah_produk : 3 }}'
            };

            loadPieTopSalesProduct(data)
        })

        $(document).ready(function() {
            let data = {
                'user_id': '{{ auth()->user()->id }}',
                'jumlah_produk': '{{ isset($jumlah_produk) ? $jumlah_produk : 3 }}'
            }
            loadPieTopSalesProduct(data);
        });
    </script>
@endprepend
