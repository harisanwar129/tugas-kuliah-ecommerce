<div class="card-header">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <select id="tahun" class="form-control">
            @php
            $check_year = \App\Models\Transaction::where('created_at', '!=', '0000-00-00')->orderBy('created_at', 'ASC')->first();
            if ($check_year) {
                $oldest_year = $check_year->created_at;
            } else {
                $oldest_year = date('Y-m-d');
            }
            $oldest_year2 = \Carbon\Carbon::parse($oldest_year)->year;
            // dd($oldest_year2);
            for($y=$oldest_year2; $y<=date("Y"); $y++){
                echo "<option value= ".$y." ".($y == date('Y') ? 'selected' : '').">" . $y . "</option>";
            }
            @endphp
        </select>
        <span class="mx-2"></span>
        <select id="bulan" class="form-control">
            <option value="1" {{ (\Carbon\Carbon::now()->format('m') == '1') ? 'selected' : '' }}>Januari</option>
            <option value="2" {{ (\Carbon\Carbon::now()->format('m') == '2') ? 'selected' : '' }}>Februari</option>
            <option value="3" {{ (\Carbon\Carbon::now()->format('m') == '3') ? 'selected' : '' }}>Maret</option>
            <option value="4" {{ (\Carbon\Carbon::now()->format('m') == '4') ? 'selected' : '' }}>April</option>
            <option value="5" {{ (\Carbon\Carbon::now()->format('m') == '5') ? 'selected' : '' }}>Mei</option>
            <option value="6" {{ (\Carbon\Carbon::now()->format('m') == '6') ? 'selected' : '' }}>Juni</option>
            <option value="7" {{ (\Carbon\Carbon::now()->format('m') == '7') ? 'selected' : '' }}>Juli</option>
            <option value="8" {{ (\Carbon\Carbon::now()->format('m') == '8') ? 'selected' : '' }}>Agustus</option>
            <option value="9" {{ (\Carbon\Carbon::now()->format('m') == '9') ? 'selected' : '' }}>September</option>
            <option value="10" {{ (\Carbon\Carbon::now()->format('m') == '10') ? 'selected' : '' }}>Oktober</option>
            <option value="11" {{ (\Carbon\Carbon::now()->format('m') == '11') ? 'selected' : '' }}>November</option>
            <option value="12" {{ (\Carbon\Carbon::now()->format('m') == '12') ? 'selected' : '' }}>Desember</option>
        </select>
    </div>
</div>
<div class="card-body">
    <div class="chart-container">
        <div id="chart_1" class="amchart"></div>
    </div>
</div>

@prepend('js')
    <script>
        let chartPenjualanHarianData;

        var theDate = new Date();
        var firstDate = new Date(theDate.getFullYear(), theDate.getMonth(), 1);
        var curDate = theDate.getDate()
        var curYear = theDate.getFullYear();
        var totalDay = new Date(theDate.getFullYear(), theDate.getMonth() + 1, 0).getDate();
        firstDate.setDate(firstDate.getDate());

        function loadLinePenjualanHarian(data = []) {
            var s = $('#chart_1').parents(".card");
            s.addClass("is-loading")
            $.ajax({
                url: "{{ route('v1.statistik.get_penjualan_harian') }}",
                dataType: 'json',
                method: 'POST',
                data: data,
                success: function(response) {
                    s.removeClass("is-loading");
                    // Create chart instance
                    typeof chartPenjualanHarianData === 'object' ? chartPenjualanHarianData.dispose() : '';
                    var chartPenjualanHarian = am4core.create("chart_1", am4charts.XYChart);

                    let title = chartPenjualanHarian.titles.create();
                    title.text = "Statistik Harian";
                    title.fontSize = 20;
                    title.marginBottom = 10;

                    am4core.useTheme(am4themes_animated);

                    // Increase contrast by taking evey second color
                    // chartPenjualanHarian.colors.step = 2;
                    // manually define color set
                    chartPenjualanHarian.colors.list = [
                        am4core.color("#77dd77"),
                        am4core.color("#FFC75F"),
                        am4core.color("#FF6F91")
                    ];

                    // Add data
                    chartPenjualanHarian.data = generateChartData();

                    // Create axes
                    var dateAxis = chartPenjualanHarian.xAxes.push(new am4charts.DateAxis());
                    dateAxis.renderer.minGridDistance = 50;
                    dateAxis.renderer.grid.template.location = 0;
                    dateAxis.renderer.grid.template.location = 0;
                    dateAxis.renderer.minGridDistance = 10;
                    dateAxis.renderer.labels.template.horizontalCenter = "right";
                    dateAxis.renderer.labels.template.verticalCenter = "middle";
                    dateAxis.renderer.labels.template.rotation = 270;
                    dateAxis.tooltip.disabled = false;
                    dateAxis.fontSize = 12;
                    dateAxis.renderer.minHeight = 110;

                    // Create series
                    function createAxisAndSeries(field, name, opposite, bullet) {
                        var valueAxis = chartPenjualanHarian.yAxes.push(new am4charts.ValueAxis());
                        if (chartPenjualanHarian.yAxes.indexOf(valueAxis) != 0) {
                            valueAxis.syncWithAxis = chartPenjualanHarian.yAxes.getIndex(0);
                        }

                        var series = chartPenjualanHarian.series.push(new am4charts.LineSeries());
                        series.dataFields.valueY = field;
                        series.dataFields.dateX = "date";
                        series.strokeWidth = 2;
                        series.yAxis = valueAxis;
                        series.name = name;
                        series.tooltipText = "{name}: [bold]{valueY}[/]";
                        series.tensionX = 0.8;
                        series.showOnInit = true;

                        var interfaceColors = new am4core.InterfaceColorSet();

                        switch (bullet) {
                            case "triangle":
                                var bullet = series.bullets.push(new am4charts.Bullet());
                                bullet.width = 12;
                                bullet.height = 12;
                                bullet.horizontalCenter = "middle";
                                bullet.verticalCenter = "middle";

                                var triangle = bullet.createChild(am4core.Triangle);
                                triangle.stroke = interfaceColors.getFor("background");
                                triangle.strokeWidth = 2;
                                triangle.direction = "top";
                                triangle.width = 12;
                                triangle.height = 12;
                                break;
                            case "rectangle":
                                var bullet = series.bullets.push(new am4charts.Bullet());
                                bullet.width = 10;
                                bullet.height = 10;
                                bullet.horizontalCenter = "middle";
                                bullet.verticalCenter = "middle";

                                var rectangle = bullet.createChild(am4core.Rectangle);
                                rectangle.stroke = interfaceColors.getFor("background");
                                rectangle.strokeWidth = 2;
                                rectangle.width = 10;
                                rectangle.height = 10;
                                break;
                            default:
                                var bullet = series.bullets.push(new am4charts.CircleBullet());
                                bullet.circle.stroke = interfaceColors.getFor("background");
                                bullet.circle.strokeWidth = 2;
                                break;
                        }

                        valueAxis.renderer.line.strokeOpacity = 1;
                        valueAxis.renderer.line.strokeWidth = 2;
                        valueAxis.renderer.line.stroke = series.stroke;
                        valueAxis.renderer.labels.template.fill = series.stroke;
                        valueAxis.renderer.opposite = opposite;
                    }

                    createAxisAndSeries("transaksi_berhasil", "Transaksi Berhasil", true, "");
                    createAxisAndSeries("transaksi_pending", "Transaksi Pending", true, "rectangle");

                    // Add legend
                    chartPenjualanHarian.legend = new am4charts.Legend();

                    // Add cursor
                    chartPenjualanHarian.cursor = new am4charts.XYCursor();
                    chartPenjualanHarian.scrollbarX = new am4core.Scrollbar();
                    chartPenjualanHarian.scrollbarY = new am4core.Scrollbar();

                    // generate some random data, quite different range
                    function generateChartData() {
                        var chartData = [];


                        var transaksi_pending = 0;
                        var transaksi_berhasil = 0;
                        for (var i = 0; i < response.length; i++) {
                            // we create date objects here. In your data, you can have date strings
                            // and then set format of your dates using chartPenjualanHarian.dataDateFormat property,
                            // however when possible, use date objects, as this will speed up chart rendering.
                            var firstDate = new Date(response[i].tahun, ("0" + (response[i].bulan + 1)).slice(-
                                2));
                            var newDate = new Date(firstDate);
                            var tmp = (parseInt(response[i].bulan, 10) - 1);
                            newDate.setDate(newDate.getDate() + i);
                            newDate.setMonth(tmp);

                            transaksi_pending = response[i].transaksi_pending;
                            transaksi_berhasil = response[i].transaksi_berhasil;

                            chartData.push({
                                date: newDate,
                                transaksi_pending: transaksi_pending,
                                transaksi_berhasil: transaksi_berhasil,
                            });
                        }
                        return chartData;
                    }

                    chartPenjualanHarianData = chartPenjualanHarian;
                }
            })
        }

        $("#date_from_chart_1, #date_to_chart_1").on('change', function(e) {
            chartPenjualanHarianData.dispose();

            let data = {
                'date_from': $("#date_from_chart_1").val(),
                'date_to': $("#date_to_chart_1").val(),
                'user_id': '{{ auth()->user()->id }}'
            };

            loadLinePenjualanHarian(data)
        })

        $("#tahun, #bulan").change(function() {
        chartPenjualanHarianData.dispose();

        let data = {
            'bulan': $("#bulan").val(),
            'tahun': $("#tahun").val(),
            'user_id': '{{ auth()->user()->id }}'
        };

        if ($('#bulan').val() == (theDate.getMonth() + 1) && $('#tahun').val() == theDate.getFullYear()) {
            // means today, limit the day only for current day
            data['jumlah_hari'] = curDate;
        }
        loadLinePenjualanHarian(data)
    })

        $(document).ready(function() {
            let data = {
                'user_id': '{{ auth()->user()->id }}',
                'bulan': ("0" + (theDate.getMonth() + 1)).slice(-2),
                'tahun': curYear,
                'jumlah_hari': curDate,
            }
            loadLinePenjualanHarian(data);
        });

    </script>
@endprepend
