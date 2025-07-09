<?php
require_once __DIR__ . '/../config/includes.php';

class listPrice extends iQuery
{
  public static function listPriceMSC()
  {
    $table =
      '<table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
				<thead style="background-color:#eed484; color:black;">
					<tr>
						<th colspan="3" style="text-align: center;">Mediterranean Shipping Company (Medlog)</th>
					</tr>
					<tr style="text-align: center;">
						<th>Tipo</th>
						<th>Gate In ($)</th>
						<th>Gate Out ($)</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">
					<tr>
						<td>20/40\' DRY</td>
						<td>$98.000</td>
						<td>$92.000</td>
					</tr>
					<tr>
						<td>20/40\' HR</td>
						<td>$98.000</td>
						<td>$95.000</td>
					</tr>
				</tbody>
			</table>';

    return $table;
  }

  public static function listPriceMaersk()
  {
    $table =
      '<table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
				<thead style="background-color:#42b0d5; color:white;">
					<tr>
						<th colspan="3" style="text-align: center;">Maersk Line (Ex Contopsa)</th>
					</tr>
					<tr style="text-align: center;">
						<th>Tipo</th>
						<th>Gate In ($)</th>
						<th>Gate Out ($)</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">
					<tr>
						<td>20/40\' DRY</td>
						<td>$139.000</td>
						<td>$133.000</td>
					</tr>
					<tr>
						<td>20/40\' HR</td>
						<td>$144.000</td>
						<td>$138.000</td>
					</tr>
				</tbody>
			</table>';

    return $table;
  }

  public static function listPriceCC()
  {
    $table =
      '<table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
				<thead style="background-color:#2A5CAA; color:white;">
					<tr>
						<th colspan="3" style="text-align: center;">Cool Carriers</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">
					<tr>
						<td>
							<div style="display:flex; justify-content:center; padding-top:2%; padding-bottom:2%;">
								<iframe src="https://www.coolcarriers.cl/wp-content/uploads/2022/11/Cool-Carriers-Surcharges-Season-2022-2023-copia-actualizada-13dic.pdf" width="1250" height="600" style="border: none;"></iframe>
              </div>
						</td>
					</tr>
				</tbody>
			</table>';

    return $table;
  }

}
