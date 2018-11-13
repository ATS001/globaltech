<?php 
/**
* MRT Highchart_Generator
*/
class MHighchart 
{
	var $titre           = NULL;
	var $width           = NULL;
	var $items           = NULL;
	var $container       = NULL;
	var $chart_rended    = NULL;
	var $chart_generated = NULL;
	var $id_chart        = NULL;
	var $chart_only      = false;


	/**
	 * [Pie_render Draw an Pie Chart]
	 * @param [type]  $table_vue [Table view with tree column (name, y, nbr )]
	 * @param integer $width     [With of container]
	 */
	public function Pie_render($table_vue, $width = 6)
	{
		global $db;
		$sql = "SELECT * FROM $table_vue";
		if(!$db->Query($sql)){
			var_dump($db->Error());
			return false;
		}else{
			if($db->RowCount())
			{
				$brut_array       = $db->RecordsArray();

                //Format Y and nbr to float value
				foreach($brut_array as $k=>$arr)
				{

					$brut_array[$k]['y']  = (float) $arr['y'];
					$brut_array[$k]['nbr']  = (float) $arr['nbr'];     

				}

				$arr_nbr_sta = $brut_array;

			}else{
				exit('no data yet');
			}


		}


		$chart = new Highchart();
		$this->container = MD5(uniqid(rand(), true));
		$this->width = $width;
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');


		$chart->chart->renderTo = $this->container;
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->exporting->enabled = true;
		$chart->title->text = $this->titre;
		$item = $this->items;


		$chart->tooltip->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ this.point.nbr +' ' + this.series.options.item;}
            ");
		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = 1;
		$chart->plotOptions->pie->dataLabels->color = "#000000";
		$chart->plotOptions->pie->dataLabels->connectorColor = "#000000";
        
		$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
			"function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y, 1) +' %'; }"
				);

        $chart->series[] = array(
        	'type' => "pie",
        	'item' => $item,
        	'data' => $arr_nbr_sta,

        	);
        $this->chart_generated = $chart->render();
        $this->Graph_render();
        return print ($this->chart_rended);

        
    }

    

    public function column_render($table_vue, $width = 6)
    {
    	global $db;
    	$db->Query("SET lc_time_names = 'fr_FR';");
		$sql = "SELECT * FROM $table_vue";
		if(!$db->Query($sql)){
			var_dump($db->Error());
			return false;
		}else{
			if($db->RowCount())
			{
				$brut_array       = $db->RecordsArray();

                //Format Y and nbr to float value
                $axes = array();
                $data = array();
				foreach($brut_array as $k=>$arr)
				{
					
					
                    array_push($axes, $arr['y']);

                    number_format($arr['nbr']);
                    array_push($data, (float) $arr['nbr']);

					/*$brut_array[$k]['y']    = $arr['y'];
					$brut_array[$k]['nbr']  = (float) $arr['nbr'];  */   

				}
                
				$arr_nbr_sta = $brut_array;

			}else{
				exit('no data yet');
			}


		}


		$chart = new Highchart();
		$this->container = MD5(uniqid(rand(), true));
		$this->width = $width;
		$chart->exporting->enabled = true;


		$chart->chart->renderTo = $this->container;
		$chart->chart->type = "column";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $this->titre;
		$chart->subtitle->text = null;
		$chart->xAxis->categories = $axes;
		$chart->yAxis->min = 0;
		$chart->yAxis->title->text = "Recette (FCFA)";
		$chart->legend->layout = "vertical";
		$chart->legend->backgroundColor = "#FFFFFF";
		$chart->legend->align = "left";
		$chart->legend->verticalAlign = "top";
		$chart->legend->x = 100;
		$chart->legend->y = 70;
		$chart->legend->floating = 1;
		$chart->legend->shadow = 1; 

		
		$chart->tooltip->formatter = new HighchartJsExpr("function() {
			return Highcharts.numberFormat(this.y, 0)+' Fcfa';}"
            );
		
		

		$chart->series[] = array(
			'name' => date('Y'),
			'data' => $data
		);
        $this->chart_generated = $chart->render();
        $this->Graph_render();
        return print ($this->chart_rended);
    }

    /**
     * [Graph_render Generate HTML code include js rended]
     */
    private function Graph_render()
    {
    	$main_box_start = $this->chart_only == false ? '<div class="col-sm-'.$this->width.'"><div class="widget-box">' : null;
    	$main_box_end   = $this->chart_only == false ? '</div><!-- /.widget-box --></div>' : null;
    	
    	$this->chart_rended = '
            '.$main_box_start .'
                    <div class="widget-header widget-header-flat widget-header-small">
                        <h5 class="widget-title">
                            <i class="ace-icon fa fa-signal"></i>
                            '.$this->titre.'
                        </h5>
                        <div class="widget-toolbar no-border">
                            			
                            <a href="#"  class="filter_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-filter"></i>
							</a>
                            <a href="#" data-action="reload" class="refrech_highchart" this_c="'.$this->container.'" id_chart="'.MInit::crypt_tp('chart', $this->id_chart).'">
								<i class="ace-icon fa fa-refresh"></i>
							</a>
						</div>
						


                    </div>
                    <div class="widget-body">
                        <div class="widget-main">

                            <div id="'.$this->container.'"></div>
                            <script type="text/javascript">'.$this->chart_generated.'</script>

                        </div><!-- /.widget-main -->
                    </div><!-- /.widget-body -->
                '.$main_box_end.'';

    }

    public function call_chart($chart)
    {
    	//Format file link
    	$file_tplt = MPATH_THEMES.'chart_template/'.$chart.'_chart.php';
    	if(!file_exists($file_tplt)){
    		exit('0#<br>Le Graph n\'existe pas, contactez l\'administrateur'.$file_tplt);
    	}

        //Evry thing ok load template
    	include_once $file_tplt;
    }
}