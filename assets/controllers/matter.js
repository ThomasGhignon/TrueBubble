import * as Matter from 'matter-js';

// module aliases
var Engine = Matter.Engine,
    Render = Matter.Render,
    Runner = Matter.Runner,
    Bodies = Matter.Bodies,
    Composite = Matter.Composite;

// create an engine
var engine = Engine.create();

var render = Render.create({
    element: document.body,
    engine: engine,
    options: {
        wireframes: false,
        background: 'transparent',
    }
});

render.canvas.width = window.innerWidth;
render.canvas.height = window.innerHeight;

Render.run(render);

const placement = {x: 1, y: 1};
const spacing = {x: 300, y: 300};

var ground = Bodies.rectangle(window.innerWidth/2, window.innerHeight, window.innerWidth, 10,
    {
        isStatic: true,
        render: {
            fillStyle: 'transparent',
        }
    });
var wallRight = Bodies.rectangle(0, window.innerHeight/2, 1, window.innerHeight,
    {
        isStatic: true,
        render: {
            fillStyle: 'transparent',
        }
    });
var wallLeft = Bodies.rectangle(window.innerWidth, window.innerHeight/2, 0, window.innerHeight,
    {
        isStatic: true,
        render: {
            fillStyle: 'transparent',
        }
    });

function addObjects () {

    var tab = [];
    document.getElementById('home').querySelectorAll('[data-object]').forEach((object, index) => {
        tab.push(fillObject(object, index));
    });

    tab.push(ground, wallRight, wallLeft);

    return tab;
}

function fillObject(object, index) {
    var data = object.getAttribute('data-object').split('|')
    console.log(data)
    return Bodies.circle(
        window.innerWidth/2,
        index,
        80, {
            friction: 100,
            label: data[2],
            render: {
                fillStyle: '#e74c3c',
                strokeStyle: "#fff",
                lineWidth: 0,
                text: {
                    fillStyle: "#000000",
                    content: "test",
                    size: 50,
                },
            }
        })
}

var boxB = Bodies.circle(
    window.innerWidth/2,
    10,
    80, {
        friction: 0.5,
        render: {
            fillStyle: '#e74c3c',
            strokeStyle: "#fff",
            lineWidth: 0
        }
    });

Composite.add(engine.world, addObjects());




Render.run(render);

var runner = Runner.create();
Runner.run(runner, engine);