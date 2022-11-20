import * as Matter from 'matter-js';

    if(document.getElementById('home')) {
    // module aliases
    var Engine = Matter.Engine,
        Render = Matter.Render,
        World = Matter.World,
        Body = Matter.Body,
        Bodies = Matter.Bodies;

    var worldW = window.innerWidth;
    var worldH = window.innerHeight;


    var engine;
    var render;

    // bodies
    var blocks = [];
    var walls = [];
    var ground;
    var ceiling;
    var wallRight;
    var wallLeft;

    // DOM elements
    var hBlocks = document.getElementsByClassName("bubble");

    function Ball(x,y,r){
        var options = {
            density: 1,
            friction: 100,
            restitution: 0,
            render: {
                fillStyle: 'transparent',
            }
        }
        this.body = Bodies.circle(x, y, r, options);
        World.add(engine.world, [this.body]);
    }

    function setup(){
        engine = Engine.create();

        render = Render.create({
            element: document.body,
            engine: engine,
            options: {
                width: window.innerWidth,
                height: window.innerHeight,
                wireframes: false,
                showAngleIndicator:false,
                background: 'transparent',
            }
        });
        Render.run(render);
        for(let i=0; i<hBlocks.length; i++){
            blocks.push(new Ball(window.innerWidth/2, 0, hBlocks[i].offsetWidth/2));
        }
        ground = Bodies.rectangle(window.innerWidth/2, window.innerHeight, window.innerWidth, 1, {
            isStatic: true,
            render: {
                fillStyle: 'transparent',
            }
        });
        wallRight = Bodies.rectangle(0, window.innerHeight/2, 1, window.innerHeight, {
            isStatic: true,
            render: {
                fillStyle: 'transparent',
            }
        });
        wallLeft = Bodies.rectangle(window.innerWidth, window.innerHeight/2, 1, window.innerHeight, {
            isStatic: true,
            render: {
                fillStyle: 'transparent',
            }
        });
    }

    function draw(){
        World.add(engine.world, [ground, wallLeft, wallRight]);
    }


    setup();
    draw();

    (function render() {
        Engine.update(engine, 20);
        for (let i = 0; i < blocks.length; i++) {
            var xTrans = blocks[i].body.position.x - hBlocks[i].offsetWidth / 2;
            var yTrans = blocks[i].body.position.y - hBlocks[i].offsetHeight / 2;
            hBlocks[i].style.transform = "translate(" + xTrans + "px, " + yTrans + "px) rotate(" + blocks[i].body.angle + "rad)";
            hBlocks[i].style.visibility = "visible";
        }
        window.requestAnimationFrame(render);
    })();

}