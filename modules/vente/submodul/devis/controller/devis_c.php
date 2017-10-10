<?php
if(!Mcfg::get('tva')){
	exit('TVA');
} 
view::load_view('devis');