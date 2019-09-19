<?php 


	function fibonaci($number){
		$dem=0;
		do{
			$dem = $dem++;
			if($dem==1 || $dem ==2){
				$total=1;
			}
			else if($dem==3){
				$total=2;
				$number1 = $total;
				$number2 =1;
			}else{
				$number2 = $total-$number2;
				$total = $number1 + $number2;
				$number1 = $total;
			}
		}while ($dem<=$number);
			return $total;
	}

	

 ?>