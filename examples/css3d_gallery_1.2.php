<?php
	header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 100000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	
	header('Set-Cookie: cross-site-cookie=bar; SameSite="None"; Secure');
?>

<!DOCTYPE html>
<!-- This file is contributed by Mukuldeep Maiti github:https://github.com/mukuldeep -->
<html>
	<head>
		<title>three.js css3d - Photo Gallery by Mukul</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="https://threejs.org/examples/main.css">
		
		<style>
			a {
				color: #8ff;
			}
			.blink {
				animation: blinker 0.8s linear infinite;
			}
			   @keyframes blinker {  
			50% { 
				opacity: 0.8; 
				box-shadow: 0px 0px 12px rgba(127,255,255,1);
				border: 1px solid rgba(127,255,255,1);
				background-color:rgba(127,255,255,0.5);
				}
			}
			#menu {
				position: absolute;
				bottom: 20px;
				width: 100%;
				text-align: center;
			}

			.showingpan {
				width: 300px;
				height: 300px;
				box-shadow: 0px 0px 30px rgba(127,255,255,1);
				border: 5px solid rgba(127,255,255,0.5);
				border-radius:10px;
				text-align: center;
			}
			
			.showingpan_message {
				font-family: "Lucida Console", "Courier New", monospace;
				margin-top:25px;
				left: 0px;
				right: 0px;
				font-size: 40px;
				color: rgba(127,255,255,0.75);
			}
			
			.element {
				width: 200px;
				height: 200px;
				box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
				border: 1px solid rgba(127,255,255,0.25);
				border-radius:2px 20px 2px 20px;
				font-family: Helvetica, sans-serif;
				text-align: center;
				line-height: normal;
				cursor: default;
			}

			.element:hover {
				box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
				border: 1px solid rgba(127,255,255,0.75);
				animation: blinker 0.8s linear infinite;
			}

				.element .number {
					position: absolute;
					top: 20px;
					right: 20px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}

				.element .symbol {
					position: absolute;
					top: 40px;
					left: 0px;
					right: 0px;
					font-size: 60px;
					font-weight: bold;
					color: rgba(255,255,255,0.75);
					text-shadow: 0 0 10px rgba(0,255,255,0.95);
				}

				.element .center {
					max-height: 150px;
					max-width: 190px;
				  display: block;
				  margin-top:20px;
				  margin-left: auto;
				  margin-right: auto;
				  width: 60%;
				}
				.element .details {
					/*position: absolute;*/
					font-family: "Lucida Console", "Courier New", monospace;
					margin-top:5px;
					left: 0px;
					right: 0px;
					font-size: 10px;
					color: rgba(127,255,255,0.75);
				}

			button {
				color: rgba(127,255,255,0.75);
				background: transparent;
				outline: 1px solid rgba(127,255,255,0.75);
				border: 0px;
				padding: 5px 10px;
				cursor: pointer;
			}

			button:hover {
				background-color: rgba(0,255,255,0.5);
			}

			button:active {
				color: #000000;
				background-color: rgba(0,255,255,0.75);
			}
		</style>
	</head>
	<body>

		<div id="info"><a href="https://threejs.org" target="_blank" rel="noopener">three.js</a> css3d - Photo Gallery by <a href="https://github.com/mukuldeep" target="_blank" rel="noopener">Mukul</a>.</div>
		<div id="container"></div>
		<div id="menu">
			<button id="slide">SlideShow(Closest)</button>
			<button id="random_slide">SlideShow(Random)</button>
			<button id="rand_move">Random Move</button>
			<button id="position_by_color">Place by color</button>
			<button id="group_by_color">Group by color</button>
			
		</div>
		<script type="module">
		
			import * as THREE from 'https://threejs.org/build/three.module.js';
			import { TWEEN } from 'https://threejs.org/examples/jsm/libs/tween.module.min.js';
			import { TrackballControls } from 'https://threejs.org/examples/jsm/controls/TrackballControls.js';
			import { CSS3DRenderer, CSS3DObject } from 'https://threejs.org/examples/jsm/renderers/CSS3DRenderer.js';
			
			class Color_obj {
				  constructor(r,g,b,a) {
					this.r = r;
					this.g = g;
					this.b = a;
					this.a = a;
				  }
				}
			
			const table=[
				
				"https://images.pexels.com/photos/2916159/pexels-photo-2916159.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2563596/pexels-photo-2563596.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/54320/rose-roses-flowers-red-54320.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/65940/dahlia-dahlias-autumn-asteraceae-65940.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/196664/pexels-photo-196664.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/5478145/pexels-photo-5478145.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/5195763/pexels-photo-5195763.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/386025/pexels-photo-386025.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2440296/pexels-photo-2440296.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/65898/beech-fagus-sylvatica-fagus-deciduous-tree-65898.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1580173/pexels-photo-1580173.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/208560/pexels-photo-208560.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1591252/pexels-photo-1591252.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/673857/pexels-photo-673857.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/158734/bird-nest-eggs-blue-158734.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/842682/pexels-photo-842682.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/842682/pexels-photo-842682.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1755243/pexels-photo-1755243.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/417173/pexels-photo-417173.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/372098/pexels-photo-372098.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2040627/pexels-photo-2040627.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/3889660/pexels-photo-3889660.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/3889742/pexels-photo-3889742.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1005711/pexels-photo-1005711.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1030875/pexels-photo-1030875.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1083822/pexels-photo-1083822.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1086178/pexels-photo-1086178.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1098520/pexels-photo-1098520.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1697912/pexels-photo-1697912.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2058498/pexels-photo-2058498.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2058498/pexels-photo-2058498.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1179863/pexels-photo-1179863.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/462118/pexels-photo-462118.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/3889695/pexels-photo-3889695.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/247502/pexels-photo-247502.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/158471/ibis-bird-red-animals-158471.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1431465/pexels-photo-1431465.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/64219/dolphin-marine-mammals-water-sea-64219.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/889929/pexels-photo-889929.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1784578/pexels-photo-1784578.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1893158/pexels-photo-1893158.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2889742/pexels-photo-2889742.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/3329858/pexels-photo-3329858.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2289409/pexels-photo-2289409.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/1402850/pexels-photo-1402850.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/52961/antelope-nature-flowers-meadow-52961.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/580900/pexels-photo-580900.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/580900/pexels-photo-580900.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/2781760/pexels-photo-2781760.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/133190/pexels-photo-133190.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/414090/pexels-photo-414090.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				"https://images.pexels.com/photos/358238/pexels-photo-358238.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500", "sample title", "sample description", 1, 1,
				 
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				"","sample title", "sample description", 1, 1,
				
				
				
	
			];

			
			var is_slide_on=0, is_slide;//used for slide show
			let camera, scene, renderer,loading_scene;//threejs camera schene renderer
			let controls;// threejs controls
			
				
			const objects = []; //cssobjects
			const targets = { table:[],rand_pos:[] };
			const obj_colors=[];
			const keywords=["red","White","Yellow","Blue","Red","Green","Black","Brown","Azure","Ivory","Teal","Silver","Purple","Navy blue","Pea green","Gray","Orange","Maroon","Charcoal","Aquamarine","Coral","Fuchsia","Wheat","Lime","Crimson","Khaki","Hot pink","Magenta","Olden","Plum","Olive","Cyan"];
			const color_names=["red","green", "blue" ,"yellow", "pink", "black", "golden", "maroon" ,"orange", "magenta","white"];
			
			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 3000;

				scene = new THREE.Scene();
				
				//the showing pan
				{
					const showing_pan=document.createElement('div');
					showing_pan.className='showingpan';
					showing_pan.style.backgroundColor = 'rgba(0,0,0,0)';
					
					const showing_pan_message= document.createElement( 'div' );
					showing_pan_message.className = 'showing_panmessage';
					showing_pan_message.innerHTML = ' SLIDE SHOW ';
					showing_pan.appendChild( showing_pan_message );
					
					const element = document.createElement( 'div' );
					element.className = 'element';
					element.style.backgroundColor = 'rgba(0,0,0,0)';
					//showing_pan.appendChild(element);
					
					
					const objectCSS = new CSS3DObject( showing_pan );
					objectCSS.position.x = 0;
					objectCSS.position.y = 0;
					objectCSS.position.z = 2599;
					scene.add( objectCSS );
					
				
				}

				//prepare 

				for ( let i = 0; i < table.length; i += 5 ) {
					const objectColor= new Color_obj(255,255,255,1);
					obj_colors.push(objectColor);
				}
				for ( let i = 0; i < table.length; i += 5 ) {

					const element = document.createElement( 'div' );
					element.className = 'element';
					element.id=i/5;
					element.style.backgroundColor = 'rgba(0,127,127,' + ( Math.random() * 0.5 + 0.25 ) + ')';

					
					var rgba_img_avg;
					const photo = document.createElement( 'img' );
					photo.className = 'center';
					photo.setAttribute('crossOrigin', '');
					photo.id="img"+i/5;
					var xxd=200+Math.floor(Math.random()*50);
					var xxc=250+Math.floor(Math.random()*50);
					
					var keyw=color_names[Math.floor(Math.random()*color_names.length)];
					photo.src="https://source.unsplash.com/"+xxc+"x"+xxd+"/?"+keyw;//table[i];
					photo.onload=function(){
						rgba_img_avg=getAverageRGB(photo);
						//console.log(rgba_img_avg);
						obj_colors[i/5].r=rgba_img_avg.r;
						obj_colors[i/5].b=rgba_img_avg.b;
						obj_colors[i/5].g=rgba_img_avg.g;
						element.style.backgroundColor = 'rgba('+rgba_img_avg.r+','+rgba_img_avg.g+','+rgba_img_avg.b+',' + ( Math.random() * 0.5 + 0.25 ) + ')';
						}
					element.appendChild( photo );
					

					const details = document.createElement( 'div' );
					details.className = 'details';
					details.innerHTML = table[ i + 1 ] + '<br>' + table[ i + 2 ];
					element.appendChild( details );

					const objectCSS = new CSS3DObject( element );
					objectCSS.position.x = Math.random() * 4000 - 2000;
					objectCSS.position.y = Math.random() * 4000 - 2000;
					objectCSS.position.z = Math.random() * 4000 - 2000;
					scene.add( objectCSS );
					objectCSS.element.onclick = function() { 
									console.log(this.id);
									console.log(targets['rand_pos'][this.id].position);
									let pos=targets['rand_pos'][this.id].position;
									let position=[pos.x,pos.y,pos.z+400];
									
									let k=0-pos.x;
									let l=0-pos.y;
									let m=2600-pos.z;
									
									for ( let i = 0; i < objects.length; i ++ ) {
										targets.rand_pos[i].position.x +=k;
										targets.rand_pos[i].position.y +=l;
										targets.rand_pos[i].position.z +=m;
										
										if(targets.rand_pos[i].position.x>2600)targets.rand_pos[i].position.x-=6000;
										if(targets.rand_pos[i].position.y>2600)targets.rand_pos[i].position.y-=6000;
										if(targets.rand_pos[i].position.z>2600)targets.rand_pos[i].position.z-=6000;
										
									}
	
									transform( targets.rand_pos, 2000 );
									
									};
						
					objects.push( objectCSS );

					//

					const object = new THREE.Object3D();
					object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
					object.position.y = - ( table[ i + 4 ] * 180 ) + 990;

					targets.table.push( object );

				}

				
				// random position

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = new THREE.Object3D();

					object.position.x = Math.random() * 4000 - 2000;;
					object.position.y = Math.random() * 4000 - 2000;;
					object.position.z = Math.random() * 4000 - 2000;;

					targets.rand_pos.push( object );

				}

				//

				renderer = new CSS3DRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.getElementById( 'container' ).appendChild( renderer.domElement );

				//

				controls = new TrackballControls( camera, renderer.domElement );
				controls.minDistance = 500;
				controls.maxDistance = 6000;
				controls.addEventListener( 'change', render );




				/*
				 * Slide buttons
				 */
				const buttonSlideShow = document.getElementById( 'slide' );
				buttonSlideShow.addEventListener( 'click', function () {
					if(is_slide_on!=1){
						is_slide_on=1;
						slide_show();
					}else{
						is_slide_on=0;
					}
				} );
				
				const buttonRandSlideShow = document.getElementById( 'random_slide' );
				buttonRandSlideShow.addEventListener( 'click', function () {
					if(is_slide_on!=2){
						is_slide_on=2;
						rand_slide_show();
					}else{
						is_slide_on=0;
					}
				} );
				
				const buttonRandMove = document.getElementById( 'rand_move' );
				buttonRandMove.addEventListener( 'click', function () {
					if(is_slide_on!=3){
						is_slide_on=3;
						rand_movement();
					}else{
						is_slide_on=0;
					}
				} );
				
				const buttonRandPositionByColor = document.getElementById( 'position_by_color' );
				buttonRandPositionByColor.addEventListener( 'click', function () {
					position_by_color_fn();
				} );
				
				const buttonGroupByColor = document.getElementById( 'group_by_color' );
				buttonGroupByColor.addEventListener( 'click', function () {
					group_by_color_fn();
				} );
				
				//transform( targets.rand_pos, 2000 );
				
				window.onload=function(){transform( targets.rand_pos, 2000 );slide_show();};

				window.addEventListener( 'resize', onWindowResize );
				
				

			}
			
			function position_by_color_fn(){
				for ( let i = 0; i < objects.length; i ++ ) {
					//console.log(obj_colors[i]);
					targets.rand_pos[i].position.x=obj_colors[i].r*10-1000;
					targets.rand_pos[i].position.y=obj_colors[i].g*10-1000;
					targets.rand_pos[i].position.z=obj_colors[i].b*10-1000;
					
				}
				transform(targets.rand_pos, 2000 );
			}
			
			// Slide show closest object to show
			function slide_show(){
				let u,v,w=0,mscore=100000,score;
				
				for ( let i = 0; i < objects.length; i ++ ) {
					score=Math.abs(targets.rand_pos[i].position.x)+Math.abs(targets.rand_pos[i].position.y)+Math.abs(targets.rand_pos[i].position.z-1000);
					if(score!=1600 && mscore>score){//targets.rand_pos[i].position.z>w  && targets.rand_pos[i].position.z<2100){
						mscore=score;
						u=targets.rand_pos[i].position.x;
						v=targets.rand_pos[i].position.y;
						w=targets.rand_pos[i].position.z;
					}
				}
				let k=0-u;
				let l=0-v;
				let m=2600-w;
				for ( let i = 0; i < objects.length; i ++ ) {
					targets.rand_pos[i].position.x +=k;
					targets.rand_pos[i].position.y +=l;
					targets.rand_pos[i].position.z +=m;
					
					if(targets.rand_pos[i].position.x>2600)targets.rand_pos[i].position.x-=6000;
					if(targets.rand_pos[i].position.y>2600)targets.rand_pos[i].position.y-=6000;
					if(targets.rand_pos[i].position.z>2600)targets.rand_pos[i].position.z-=6000;
					
				}
				transform( targets.rand_pos, 2000 );
				if(is_slide_on==1){
					setTimeout(slide_show,4000);
				}
			}
			// slide show random object to show
			function rand_slide_show(){
				let u,v,w;
				var pos;
				pos=Math.floor((Math.random() * 4000)%objects.length);
				console.log(pos);
				u=targets.rand_pos[pos].position.x;
				v=targets.rand_pos[pos].position.y;
				w=targets.rand_pos[pos].position.z;

				let k=0-u;
				let l=0-v;
				let m=2600-w;
				for ( let i = 0; i < objects.length; i ++ ) {
					targets.rand_pos[i].position.x +=k;//Math.random() * 4000 - 2000;
					targets.rand_pos[i].position.y +=l;//Math.random() * 4000 - 2000;
					targets.rand_pos[i].position.z +=m;//Math.random() * 4000 - 2000;
					
					if(targets.rand_pos[i].position.x>2600)targets.rand_pos[i].position.x-=6000;
					if(targets.rand_pos[i].position.y>2600)targets.rand_pos[i].position.y-=6000;
					if(targets.rand_pos[i].position.z>2600)targets.rand_pos[i].position.z-=6000;
					
				}
				transform( targets.rand_pos, 2000 );
				if(is_slide_on==2){
					setTimeout(rand_slide_show,4000);
				}
			}
			//  random object movement
			function rand_movement(){
				
				for ( let i = 0; i < objects.length; i ++ ) {
					targets.rand_pos[i].position.x =Math.random() * 4000 - 2000;
					targets.rand_pos[i].position.y =Math.random() * 4000 - 2000;
					targets.rand_pos[i].position.z =Math.random() * 4000 - 2000;
					
				}
				transform( targets.rand_pos, 2000 );
				if(is_slide_on==3){
					setTimeout(rand_movement,4000);
				}
			}
			function group_by_color_fn(){
				var max_dist=50;
				var cnt=0;
				var groups=[];
				var vis = new Array(obj_colors.length).fill(0);
				while(cnt<obj_colors.length){
					var smallest=0,mn=100000000,dist;
					for ( let i = 0; i < obj_colors.length; i++ ) {
						if(vis[i]==0){
							dist=obj_colors[i].r*32000+obj_colors[i].g*255+obj_colors[i].b;
							if(mn>dist){
								smallest=i;
								mn=dist;
							}
						}
					}
					cnt++;
					console.log("smallest:"+smallest+" "+cnt);
					var grp=[];
					
					grp.push(smallest);
					vis[smallest]=1;
					
					console.log("cnt:"+cnt);
					var x=obj_colors[smallest].r,y=obj_colors[smallest].g,z=obj_colors[smallest].b;
					
					for ( let i = 0; i < obj_colors.length; i ++ ) {
						if(vis[i]==0){
							dist=(obj_colors[i].r-obj_colors[smallest].r)*(obj_colors[i].r-obj_colors[smallest].r)+(obj_colors[i].g-obj_colors[smallest].g)*(obj_colors[i].g-obj_colors[smallest].g)+(obj_colors[i].b-obj_colors[smallest].b)*(obj_colors[i].b-obj_colors[smallest].b);
							dist=Math.sqrt(dist);
							if(dist<max_dist){
								grp.push(i);
								x+=obj_colors[i].r;
								y+=obj_colors[i].g;
								z+=obj_colors[i].b;
								vis[i]=1;
								cnt++;
							}
						}
					
					}
					x/=grp.length;
					y/=grp.length;
					z/=grp.length;
					console.log(grp);
					for ( let i = 0; i < grp.length; i ++ ) {
						targets.rand_pos[grp[i]].position.x =x*10-1000;
						targets.rand_pos[grp[i]].position.y =y*10-1000;
						targets.rand_pos[grp[i]].position.z =z*10-1000 +(i*100);
					}
					//groups.push(grp);
				
				
				}
				
				/*for ( let j = 0; j < groups.length; j ++ ) {
					var gp=groups[j];
					var x=0,y=0;
					for ( let i = 0; i < gp.length; i ++ ) {
						x+=obj_colors[gp[i]].r;
						y+=obj_colors[gp[i]].b;
					}
					x/=gp.length;
					y/=gp.length;
					
					for ( let i = 0; i < gp.length; i ++ ) {
						targets.rand_pos[i].position.x =x*20-1000;
						targets.rand_pos[i].position.y =y*20-1000;
						targets.rand_pos[i].position.z =(i*30)*20-1000;
					}
				}
				*/
			
				transform( targets.rand_pos, 2000 );
			
			
			}
			function tweenCamera(camera, position, duration) {        
				new TWEEN.Tween(camera.position).to({
				  x: position[0],
				  y: position[1],
				  z: position[2]
				}, duration)
				.easing(TWEEN.Easing.Exponential.InOut)
				.onComplete(function () {
						camera.lookAt(new THREE.Vector3(0,0,position[2]<0?1:-1));
					  })
				.start();
			  }

			/*
			 * Tween tranformation of objects
			 */
			function transform( targets, duration ) {

				TWEEN.removeAll();

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = objects[ i ];
					const target = targets[ i ];

					new TWEEN.Tween( object.position )
						.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

					new TWEEN.Tween( object.rotation )
						.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

				}

				new TWEEN.Tween( this )
					.to( {}, duration * 2 )
					.onUpdate( render )
					.start();

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			function animate() {

				requestAnimationFrame( animate );

				TWEEN.update();

				controls.update();

			}

			function render() {
				
				renderer.render( scene, camera );


			}
			
			/*
			  get average color of an image
			*/
			function getAverageRGB(imgEl) {
				imgEl.setAttribute('crossOrigin', '');
				
				var blockSize = 5, // only visit every 5 pixels
					defaultRGB = {r:127,g:255,b:255}, // for non-supporting envs
					canvas = document.createElement('canvas'),
					context = canvas.getContext && canvas.getContext('2d'),
					data, width, height,
					i = -4,
					length,
					rgb = {r:0,g:0,b:0},
					count = 0;
					
				if (!context) {
					console.log("get Average color is not supported!");
					return defaultRGB;
				}
				
				height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
				width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;
				
				context.drawImage(imgEl, 0, 0);
				
				try {
					data = context.getImageData(0, 0, width, height);
				} catch(e) {
					/* security error, img on diff domain *///alert('x');
					return defaultRGB;
				}
				
				length = data.data.length;
				
				while ( (i += blockSize * 4) < length ) {
					++count;
					rgb.r += data.data[i];
					rgb.g += data.data[i+1];
					rgb.b += data.data[i+2];
				}
				
				// ~~ used to floor values
				rgb.r = ~~(rgb.r/count);
				rgb.g = ~~(rgb.g/count);
				rgb.b = ~~(rgb.b/count);
				return rgb;
				
			}


		</script>
	</body>
</html>
