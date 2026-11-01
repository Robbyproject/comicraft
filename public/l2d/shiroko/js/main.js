let canvas;
let gl;
let shader;
let batcher;
let mvp = new spine.webgl.Matrix4();
let assetManager;
let skeletonRenderer;

let lastFrameTime;
let spineData;
let fps;

const BINARY_PATH = 'Shiroko_ridingsuit_home.skel';
const ATLAS_PATH = 'Shiroko_ridingsuit_home.atlas';
const WELCOME_ANIMATION = 'Start_Idle_01';
const LOOPING_ANIMATION = 'Idle_01';

// Pengaturan Skala & Posisi Shiroko
let customScale = 1.15; // 1.0 = pas 100% layar, 1.15 = proporsi ideal nyaman
let targetFps = 30;

function init() {
    window.wallpaperPropertyListener = {
        applyUserProperties: (props) => {
            if (props.scale) customScale = props.scale.value;
            if (props.targetfps) targetFps = props.targetfps.value;
        }
    };

    canvas = document.getElementById('canvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    // Aktifkan alpha transparan murni
    let config = { alpha: true, premultipliedAlpha: false };
    gl = canvas.getContext('webgl', config) || canvas.getContext('experimental-webgl', config);
    if (!gl) {
        alert('WebGL is unavailable.');
        return;
    }

    shader = spine.webgl.Shader.newTwoColoredTextured(gl);
    batcher = new spine.webgl.PolygonBatcher(gl);
    mvp.ortho2d(0, 0, canvas.width - 1, canvas.height - 1);
    skeletonRenderer = new spine.webgl.SkeletonRenderer(gl);
    assetManager = new spine.webgl.AssetManager(gl);

    assetManager.loadBinary(BINARY_PATH);
    assetManager.loadTextureAtlas(ATLAS_PATH);

    // Event listener resize otomatis saat zoom 75%, 100%, atau F11
    window.addEventListener('resize', resize);

    requestAnimationFrame(load);
}

function load() {
    if (assetManager.isLoadingComplete()) {
        spineData = loadSpineData(false);
        lastFrameTime = Date.now() / 1000;
        resize(); // Inisialisasi ukuran awal
        requestAnimationFrame(render);
    } else {
        requestAnimationFrame(load);
    }
}

function loadSpineData(premultipliedAlpha) {
    let atlas = assetManager.get(ATLAS_PATH);
    let atlasLoader = new spine.AtlasAttachmentLoader(atlas);
    let skeletonBinary = new spine.SkeletonBinary(atlasLoader);

    skeletonBinary.scale = 1;
    let skeletonData = skeletonBinary.readSkeletonData(assetManager.get(BINARY_PATH));
    let skeleton = new spine.Skeleton(skeletonData);
    let bounds = calculateSetupPoseBounds(skeleton);

    let animationStateData = new spine.AnimationStateData(skeleton.data);
    let animationState = new spine.AnimationState(animationStateData);

    animationState.addAnimation(0, WELCOME_ANIMATION, false);
    animationState.addAnimation(0, LOOPING_ANIMATION, true, 0);

    return { skeleton: skeleton, state: animationState, bounds: bounds, premultipliedAlpha: premultipliedAlpha };
}

function calculateSetupPoseBounds(skeleton) {
    skeleton.setToSetupPose();
    skeleton.updateWorldTransform();
    let offset = new spine.Vector2();
    let size = new spine.Vector2();
    skeleton.getBounds(offset, size, []);
    return { offset: offset, size: size };
}

function render() {
    let now = Date.now() / 1000;
    let delta = now - lastFrameTime;
    lastFrameTime = now;

    // Bersihkan canvas dengan transparan murni (0,0,0,0) agar tidak ada kotak abu-abu
    gl.clearColor(0, 0, 0, 0);
    gl.clear(gl.COLOR_BUFFER_BIT);

    let skeleton = spineData.skeleton;
    let state = spineData.state;
    let premultipliedAlpha = spineData.premultipliedAlpha;
    state.update(delta);
    state.apply(skeleton);
    skeleton.updateWorldTransform();

    shader.bind();
    shader.setUniformi(spine.webgl.Shader.SAMPLER, 0);
    shader.setUniform4x4f(spine.webgl.Shader.MVP_MATRIX, mvp.values);

    batcher.begin(shader);
    skeletonRenderer.premultipliedAlpha = premultipliedAlpha;
    skeletonRenderer.draw(batcher, skeleton);
    batcher.end();

    shader.unbind();

    let elapsed = Date.now() / 1000 - now;
    let targetFrameTime = 1 / targetFps;
    let delay = Math.max(targetFrameTime - elapsed, 0) * 1000;

    setTimeout(() => {
        requestAnimationFrame(render);
    }, delay);
}

// Rumus Aspect-Ratio Cover: Menjamin Shiroko selalu full 100% tanpa batas hitam di zoom berapa pun
// Rumus Presisi: Menjamin dasar gambar selalu menyentuh lantai layar saat F11
function resize() {
    if (!spineData) return;

    // 1. Gunakan window.innerWidth & innerHeight agar langsung membaca ukuran saat F11
    let w = window.innerWidth;
    let h = window.innerHeight;

    if (canvas.width !== w || canvas.height !== h) {
        canvas.width = w;
        canvas.height = h;
    }

    let bounds = spineData.bounds;
    let centerX = bounds.offset.x + bounds.size.x / 2;
    let centerY = bounds.offset.y + bounds.size.y / 2;

    let canvasRatio = w / h;
    let boundsRatio = bounds.size.x / bounds.size.y;

    let viewWidth, viewHeight;

    // Gunakan customScale 1.12 agar gambar memiliki ruang napas dan tidak pernah menyisakan celah
    let scaleVal = (typeof customScale !== 'undefined') ? customScale : 1.12;

    // Logika Cover: Mengisi penuh layar dari ujung ke ujung
    if (canvasRatio > boundsRatio) {
        viewWidth = bounds.size.x / scaleVal;
        viewHeight = viewWidth / canvasRatio;
    } else {
        viewHeight = bounds.size.y / scaleVal;
        viewWidth = viewHeight * canvasRatio;
    }

    // 2. KUNCI ANTI-CELAH HITAM: 
    // Turunkan titik tengah kamera sebesar 7% agar dasar jalanan (road) menempel rapat ke lantai layar
    let offsetY = bounds.size.y * 0.07;
    let cameraY = centerY - offsetY;

    mvp.ortho2d(centerX - viewWidth / 2, cameraY - viewHeight / 2, viewWidth, viewHeight);
    gl.viewport(0, 0, canvas.width, canvas.height);
}

init();