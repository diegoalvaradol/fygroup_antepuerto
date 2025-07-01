<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  $html = '<div style="text-align: center; margin-bottom: 1rem;">';
  $html .= '<a href="../controllers/exportReportPDF.php?id=' . intval($id) . '" download class="btn btn-mn btn-success">';
  $html .= '<i class="fa-solid fa-file-pdf"></i> Descargar PDF de Liquidación';
  $html .= '</a>';
  $html .= '</div>';

  echo $html;
}
