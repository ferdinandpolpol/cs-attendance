<?php 
include 'config.php';
header('Content-Type: text/html; charset=utf-8');

$a = "SELECT id as 'barcode', concat(fname, ' ', lname) as 'name'
FROM   attendance a1, student
WHERE  id=barcode and a1.eventid = 1 AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=2 or a2.eventid=3) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=4) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=5) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=8 or a2.eventid=9) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=11 or a2.eventid=12 or a2.eventid=13) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=11 or a2.eventid=12 or a2.eventid=15) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=11 or a2.eventid=12 or a2.eventid=16) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=11 or a2.eventid=12 or a2.eventid=17) AND
      EXISTS (SELECT *
                   FROM   attendance a2
                   WHERE  a1.id = a2.id AND
                          a2.eventid=18)
";


$q = mysqli_query($conn,$a);
$barcode = "";
$name = "";
$i=0;
while($row = mysqli_fetch_array($q))
{
	$a = $row['barcode'];
	$b = $row['name'];
	if($i == 0)
	{
		$barcode = $barcode."'$a'";
		$name = $name ."'$b'";	
		$i = 1;
	}
	else
	{
		$barcode = $barcode.",'$a'";
		$name = $name.",'$b'";	
	}
}
?>

<html>
	<head>
		<style>
		body{
			margin:0px;
			width:100%;
			height:100%;
			overflow: hidden;
			
			
		}
		.square{
			width:0px;
			height:0px;
			margin:0px;
			border-width:20px;
			
			border-style:solid;
			position:relative;
			float:left;
			border-color:transparent;
			z-index :3
			
		}
		#mainBody{
			margin:0px;
			width:100%;
			height:100%;
			z-index :2
			
		}
		#modal1
		{
			height:100%;
			width:100%;
			background-color:black;
			opacity:0.0;
			color:white;
			border-width:1px;
			border-style:solid;
			border-color: rgba(0,0,0,.9);
			position:absolute;
			z-index:999999;
			
			
			
		}
		#name{
			width:inherit;
			font-size:10vw;
			text-align:center;
			color:white;
			margin:0 auto;
		}
		</style>
	</head>
	<body>
		<div id='mainBody'>
		</div>
		<div id='modal1' onclick='change_stage()'><div id='name'></div></div>
		<input type='hidden' id='it' value='1000'/>
		<input type='hidden' id='stage' value='1'/>
		<input type='hidden' id='x' value='0'/>
		<input type='hidden' id='y' value='0'/>
		<script>
			function get(id)
			{
				return document.getElementById(id);
			}
			function getNumberBlock(centerId)
				{
					
					var x;
					var y;
					var block=[];
					
					centerId = (centerId.split("x"))[1];
					centerId = centerId.split("y");
					
					x= centerId[0];
					y= centerId[1] - 2;
					
					for (i=0; i<5; i++)//y-axis
					{
						for(j=0;j<5;j++)//x-axis
						{
							block.push("x"+(parseInt(x)+i)+"y"+(parseInt(y)+j));//ids
						
						}
					}
					
					return block;
					
				//	return 1;
					
				}
			function change_stage()
			{
				var stage = parseInt(get('stage').value);
				if (stage == 1)
				{
					get('stage').value = parseInt(2);
				}
				if(stage == 3)
				{
					get('stage').value = parseInt(4);
				}
				if(stage == 5)
				{
					get('stage').value = parseInt(6);
				}
			
			}
			function windowHandler()
			{
				this.xlen = get('mainBody').offsetWidth;
				this.ylen = get('mainBody').offsetHeight;
				this.xit = Math.floor(this.xlen/40);//divs  are 20 px, gi times 2 nako kay 1px = 2 units
				this.yit = Math.floor(this.ylen/40);
				this.digits = [];
				this.block = [];
				this.exemptdivs=[];
				this.centerblock="";
				this.modal1opacity =0;
				this.populate = function()
				{
					var s ="";
					
					//get the actualt with
					//should-be-width = currentwidth - overlapping
					
					// = (current-width[units] - (current-width - (this.xit*40) ))    [units]
					
					get('mainBody').style.width  = ((this.xlen - (this.xlen - (this.xit*40) )))+"px";
					get('mainBody').style.height  = ((this.ylen - (this.ylen - (this.yit*40) )))+"px";
					
					var curx = get('mainBody').offsetWidth; //already in px
					var cury = get('mainBody').offsetHeight;//already in px
					
					//old-width - new width /2
					
					get('mainBody').style.marginLeft = ((this.xlen - curx)/2)+"px";
					get('mainBody').style.marginRight = ((this.xlen - curx)/2)+"px";
					get('mainBody').style.marginTop = ((this.ylen - cury)/2)+"px";
					get('mainBody').style.marginBottom = ((this.ylen - cury)/2)+"px";
					
					
					
					var xit = this.xit;
					var yit = this.yit;
					
					for (j=0;j<yit;j++)
					{
						for (i=0; i <xit; i++)
						{
							s=s+"<div class='square' id='x"+j+"y"+i+"' onclick='change_stage()'></div>";
						}	
					}
					
					
					get('mainBody').innerHTML = s;
				}
				this.randomColor = function()
				{
					var color = ['1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'];
					while (true)
					{
						var cstring="";					
						for (i=0;i<6;i++)
						{
							cstring = cstring+color[Math.floor(Math.random()*15)];
						}
						if (cstring == "ffffff")
						{
							continue;
						}
						else
							break;
					}
					
					return "#"+cstring;
				}
				this.randomColorFill = function()
				{	
					
					var ranx =  Math.floor(Math.random()*this.yit);
					var rany =Math.floor(Math.random()*this.xit);
					var type =Math.floor(Math.random()*6) ;
					
					
						if(type==0)
						{
							get("x"+ranx+"y"+rany).style.borderTopColor = this.randomColor();
						}
						else if(type==1)
						{
							get("x"+ranx+"y"+rany).style.borderRightColor = this.randomColor();
						}
						else if(type==2)
						{
							get("x"+ranx+"y"+rany).style.borderBottomColor = this.randomColor();
						}
						else if(type==3)
						{
							get("x"+ranx+"y"+rany).style.borderLeftColor = this.randomColor();
						}
						else if(type==4)
						{
							get("x"+ranx+"y"+rany).style.borderTopColor = this.randomColor();
							get("x"+ranx+"y"+rany).style.borderRightColor = this.randomColor();
						}
						else if (type=5)
						{
							get("x"+ranx+"y"+rany).style.borderBottomColor = this.randomColor();
							get("x"+ranx+"y"+rany).style.borderLeftColor = this.randomColor();
						}
				
				}
				
				
				this.handleScreen = function()
				{
					var stage = get('stage').value;
					var col = 1;
					
						
					if(stage ==1)
					{
						//var it = get('it').value ;
						myHandler.randomColorFill();
						//if(it !=0)
							//get('it').value = parseInt(get('it').value) - 1;
						//else
							//get('stage').value = 2;
					}
					else if (stage ==2 )
					{
						var i =0;
						for(i=0;i<6;i++)
						{
							this.getDigitBlock(col);
							col = col+5;
							
							
						}
							
							
						
						
						this.displayDigit();
						this.clearDigit();
						get('stage').value = 3;
					}
					else if(stage == 3)
					{
						this.clearDigit();
						this.randomColorFillResult();
					}
					else if (stage == 4)
					{
						
						var curx = get('mainBody').offsetWidth; //already in px
						var cury = get('mainBody').offsetHeight;//already in px
						get('modal1').style.borderLeftWidth = ((this.xlen - curx)/2)+"px";
						get('modal1').style.borderRighttWidth = ((this.xlen - curx)/2)+"px";
						get('modal1').style.borderToptWidth = ((this.ylen - cury)/2)+"px";
						get('modal1').style.borderBottomWidth = ((this.ylen - cury)/2)+"px";
						
						get('modal1').style.marginTop = "-"+(cury + (this.ylen - cury))+"px";
						
						var df  = Math.abs(get('modal1').offsetHeight - get('name').offsetHeight)/2;
						
						get('name').style.paddingTop=df+"px";
						get('stage').value = 5;
					}
					else if (stage == 5)
					{	
							
							get('modal1').style.opacity = 0.9;
						
						
						
						this.randomColorFillResult();
						this.clearDigit();
						
					}
					else if(stage == 6)
					{
						get('modal1').style.marginTop = "0px";
						this.modal1opacity=0;
						get('stage').value = 1;
						get('x').value = 0;
						get('y').value = 0;
						this.exemptdivs = [];
						this.block = [];
					}
				}
				this.getXpos = function()
				{
					if(this.yit%2==0)
						return Math.floor(this.yit/2)-1;
					else
						return Math.floor(this.yit/2);
				}
				this.getDigitBlock = function(y)
				{
					var xcenter = this.getXpos();
					var xtop = xcenter -2;
					block = [];
					
					it = 0;
					for(i=0;i<5;i++)
					{
						for(j=0;j<4;j++)
						{
							block.push("x"+(xtop + i)+"y"+(y+j));
							
						}
						it = it+1;
					}
					
					this.block.push(block);
					
					
				}
				this.displayDigit =function()
				{
					
					var blist  = [<?php echo $barcode;?>];
					var nlist  = [<?php echo $name;?>];
					var index = Math.floor(Math.random()*blist.length);
					var randnum = blist[index];
					get('name').innerHTML = nlist[index];
					var n1 = (randnum.split(""))[0];
					var n2 = (randnum.split(""))[1];
					var n3 = (randnum.split(""))[2];
					var n4 = (randnum.split(""))[3];
					var n5 = (randnum.split(""))[4];
					var n6 = (randnum.split(""))[5];
					var block = [];
					
					for (i=0;i<this.block.length;i++)
					{
						block = this.block[i];
						if(i==0)
						{
							num = n1;
						}
						else if(i==1)
						{
							num=n2;
						}
						else if(i==2)
						{
							num=n3;
						}
						else if(i==3)
						{
							num=n4;
						}
						else if(i==4)
						{
							num=n5;
						}
						else if(i==5)
						{
							num=n6;
						}
					
						//
						
						if(num=='1'){
							//this.exemptdivs.push(block[0])
							//this.exemptdivs.push(block[1])
							//this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							//this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							//this.exemptdivs.push(block[9])
							//this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							//this.exemptdivs.push(block[17])
							//this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='2'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							//this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							//this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='3'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							//this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='4'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							//this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							//this.exemptdivs.push(block[17])
							//this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='5'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							//this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='6'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							//this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='7'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							//this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							//this.exemptdivs.push(block[9])
							//this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							//this.exemptdivs.push(block[17])
							//this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='8'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='9'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							//this.exemptdivs.push(block[17])
							//this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='0'){
							//this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							//this.exemptdivs.push(block[4])
							this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							this.exemptdivs.push(block[7])
							
							//this.exemptdivs.push(block[8])
							this.exemptdivs.push(block[9])
							//this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							//this.exemptdivs.push(block[12])
							this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							//this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						else if(num=='G'){
							this.exemptdivs.push(block[0])
							this.exemptdivs.push(block[1])
							this.exemptdivs.push(block[2])
							this.exemptdivs.push(block[3])
							
							this.exemptdivs.push(block[4])
							//this.exemptdivs.push(block[5])
							//this.exemptdivs.push(block[6])
							//this.exemptdivs.push(block[7])
							
							this.exemptdivs.push(block[8])
							//this.exemptdivs.push(block[9])
							this.exemptdivs.push(block[10])
							this.exemptdivs.push(block[11])
							
							this.exemptdivs.push(block[12])
							//this.exemptdivs.push(block[13])
							//this.exemptdivs.push(block[14])
							this.exemptdivs.push(block[15])
							
							this.exemptdivs.push(block[16])
							this.exemptdivs.push(block[17])
							this.exemptdivs.push(block[18])
							this.exemptdivs.push(block[19])
						}
						
						
					}
						
					

				}
				this.clearDigit = function()
				{
					
					var x = get('x').value;
					var y = get('y').value;
					var id = "x"+x+"y"+y;
					
					if( y < this.xit)
					{
						if(x < this.yit)
						{
							if(this.exemptdivs.indexOf(id) == -1)
							{
								get(id).style.borderColor = "transparent";
							}
							get('x').value = parseInt(x)+1; 
						}
						else
						{	
							get('x').value = 0;
							get('y').value = parseInt(y)+1; 
						}
						
					}
					else
						get('y').value = 0;
				}
				this.randomColorFillResult = function()
				{
					
					
					var rand = this.exemptdivs[Math.floor(Math.random()*this.exemptdivs.length)];
					
					var type =Math.floor(Math.random()*6) ;
					
					
					if(type==0)
					{
						get(rand).style.borderTopColor = this.randomColor();
					}
					else if(type==1)
					{
						get(rand).style.borderRightColor = this.randomColor();
					}
					else if(type==2)
					{
						get(rand).style.borderBottomColor = this.randomColor();
					}
					else if(type==3)
					{
						get(rand).style.borderLeftColor = this.randomColor();
					}
					else if(type==4)
					{
						get(rand).style.borderTopColor = this.randomColor();
						get(rand).style.borderRightColor = this.randomColor();
					}
					else if (type=5)
					{
						get(rand).style.borderBottomColor = this.randomColor();
						get(rand).style.borderLeftColor = this.randomColor();
					}
				
				}
				
				
			}
			
			var myHandler = new windowHandler();
			
			myHandler.populate();
			
			setInterval(function(){myHandler.handleScreen()},1);
			
			
			
			
		
		</script>
	</body>
</html>