var app = angular.module('genApp', []);

var transformmc = function(mc){
	var mret = "";
	for(var i=0;i<8;i++)
	{
		switch(mc[i])
		{
		case '3': mret += '0'; break;
		case '6': mret += '1'; break;
		case '8': mret += '2'; break;
		case 'q':
		case 'Q': mret += '3'; break;
		case '2': mret += '4'; break;
		case '9': mret += '5'; break;
		case '7': mret += '6'; break;
		case '4': mret += '7'; break;
		case 'g':
		case 'G': mret += '8'; break;
		case '5': mret += '9'; break;
		case 'n':
		case 'N': mret += 'A'; break;
		case 'z':
		case 'Z': mret += 'B'; break;
		case 'k':
		case 'K': mret += 'C'; break;
		case 'l':
		case 'L': mret += 'D'; break;
		case '0': mret += 'E'; break;
		case '1': mret += 'F'; break;
		}
	}
	if(mret.length==8)
		return mret;
	else
		return '';
}

var transformld(ndate){
	var nret = 26112;
	if(ndate>0){
		var sdate = ndate.toString();
		var sret = '';
	   for(var i=0;i<4;i++)
	   {
		  switch(sdate[i])
		  {
			 case '0': sret += '6';break;
			 case '1': sret += '4';break;
			 case '2': sret += '8';break;
			 case '3': sret += '1';break;
			 case '4': sret += '9';break;
			 case '5': sret += '5';break;
			 case '6': sret += '0';break;
			 case '7': sret += '3';break;
			 case '8': sret += '2';break;
			 case '9': sret += '7';break;
		  }
	   }
	   nret = sret.toString(16);
	}
	return nret;
}

var getmc = function(mc){
	if(!mc){
		return "";
	}else if(mc.length==8){
		return transformmc(mc);
	}else{
		var arr = mc.split("-");
		if(arr.length==2){
			var mret = "";
			for(var i=0;i<2;i++){
				var pad = "3333";
				mret += pad.substring(0, pad.length - arr[i].length) + arr[i];
			}
			return transformmc(mret);

		}else{
			return "";
		}
	}
};

app.controller('mainController', function ($scope) {
	
	$scope.changed = function(){
		$scope.mcode = getmc($scope.mc);
		if($scope.mcode){
			$scope.dwId = parseInt($scope.mcode,16)
			$scope.w1 = $scope.dwId >>> 16;
			$scope.w2 = $scope.dwId & 65535;
			$scope.sw1 = $scope.w1.toString(16);
			$scope.sw2 = $scope.w2.toString(16);
			$scope.wTmp = $scope.w1 ^ $scope.w2;
			$scope.swTmp = $scope.wTmp.toString(16);
			$scope.b1 = $scope.wTmp & 255;
			$scope.b2 = $scope.wTmp >>> 8;
			$scope.sb1 = $scope.b1.toString(16);
			$scope.sb2 = $scope.b2.toString(16);
			
			$scope.code = ($scope.b2 << 16) + $scope.b1;
			$scope.scode = $scope.code.toString(16);
		}
	};

	$scope.generate = function(){
		if($scope.mcode){
			
		}
	};
	
	$scope.mc = '5gnzlk4g';
	$scope.ld = 5809;
	$scope.changed();
});

