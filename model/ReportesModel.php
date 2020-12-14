<?php


class ReportesModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function graphAvailabilityReports($data){
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
}