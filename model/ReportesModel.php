<?php


class ReportesModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function graphDriverAvailabilityReports($data){
        $chart = new PieChart(700, 300);
        $filename = "tmp/charts/choferesDisponibles.png";
        $dataSet = new XYDataSet();
        $dataSet->addPoint(new Point("Disponibles (".$data["disponibilidad"]["disponibles"].")",
            $data["disponibilidad"]["disponibles"]));
        $dataSet->addPoint(new Point("No disponibles (".$data["disponibilidad"]["noDisponibles"].")",
            $data["disponibilidad"]["noDisponibles"]));
        $chart->setDataSet($dataSet);

        $chart->setTitle("Disponibilidad de choferes (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphVehicleAvailabilityReports($data){
        $chart = new PieChart(700, 300);
        $filename = "tmp/charts/vehiculosDisponibles.png";
        $dataSet = new XYDataSet();
        $dataSet->addPoint(new Point("Disponibles (".$data["disponibilidad"]["disponibles"].")",
            $data["disponibilidad"]["disponibles"]));
        $dataSet->addPoint(new Point("No disponibles (".$data["disponibilidad"]["noDisponibles"].")",
            $data["disponibilidad"]["noDisponibles"]));
        $chart->setDataSet($dataSet);

        $chart->setTitle("Disponibilidad de choferes (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphRoleReports($data){
        $chart = new PieChart(700, 300);
        $filename = "tmp/charts/totalRoles.png";
        $dataSet = new XYDataSet();
        $dataSet->addPoint(new Point("Administradores (".$data["cantidadRol"]["administradores"].")",
            $data["cantidadRol"]["administradores"]));
        $dataSet->addPoint(new Point("Supervisores (".$data["cantidadRol"]["supervisores"].")",
            $data["cantidadRol"]["supervisores"]));
        $dataSet->addPoint(new Point("Mecánicos (".$data["cantidadRol"]["mecanicos"].")",
            $data["cantidadRol"]["mecanicos"]));
        $dataSet->addPoint(new Point("Choferes (".$data["cantidadRol"]["choferes"].")",
            $data["cantidadRol"]["choferes"]));
        $chart->setDataSet($dataSet);

        $chart->setTitle("Cantidad de empleados por rol (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphKmDrivers($data){
        $chart = new VerticalBarChart(600, 200);
        $filename = "tmp/charts/kmPorChofer.png";
        $dataSet = new XYDataSet();
        foreach($data["kmPorChofer"] as $valor){
            $dataSet->addPoint(new Point($valor["dni"],
                $valor["TotalViajado"]));
        }

        $chart->setDataSet($dataSet);
        $chart->setTitle("Km recorridos por chofer (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphVehicleCost($data){
        $chart = new VerticalBarChart(600, 200);
        $filename = "tmp/charts/costoServicePorVehiculo.png";
        $dataSet = new XYDataSet();
        foreach($data["gastoService"] as $valor){
            $dataSet->addPoint(new Point($valor["Patente"],
                $valor["Total"]));
        }

        $chart->setDataSet($dataSet);
        $chart->setTitle("Gasto de service por vehículo (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphAverageFuel($data){
        $chart = new VerticalBarChart(600, 200);
        $filename = "tmp/charts/consumoPromedio.png";
        $dataSet = new XYDataSet();
        foreach($data["promedioConsumo"] as $valor){
            $dataSet->addPoint(new Point($valor["Vehiculo"],
                round($valor["Promedio"],2)));
        }

        $chart->setDataSet($dataSet);
        $chart->setTitle("Consumo promedio de combustible (en litros) (".$data["fecha"].")");
        $chart->render($filename);
    }
    public function graphTotalKm($data){
        $chart = new VerticalBarChart(600, 200);
        $filename = "tmp/charts/kmPorVehiculo.png";
        $dataSet = new XYDataSet();
        foreach($data["kmRecorridos"] as $valor){
            $dataSet->addPoint(new Point($valor["Vehiculo"],
                round($valor["TotalViajado"],2)));
        }

        $chart->setDataSet($dataSet);
        $chart->setTitle("Km recorridos por vehículo (".$data["fecha"].")");
        $chart->render($filename);
    }
}