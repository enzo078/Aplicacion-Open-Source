<?= view('layouts/header', ['title' => 'Página principal']) ?>

<style>
    .hero {
        background-color:rgb(19, 57, 105); 
        color: white;
        text-align: center;
        padding-bottom: 10rem;
    }
    
 .hero h1 {
        font-size: 4rem;
        padding-top: 10rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        display: inline-block; 
        transition: all 0.4s ease;
        position: relative;
    }

    .hero h1:hover {
        transform: scale(1.05); 
    }

    .hero h1::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 0;
        height: 3px;
        background-color: white;
        transition: width 0.3s ease 0.2s; 
    }

    .hero h1:hover::after {
        width: 100%;
    }

    .hero p {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 2rem auto 2rem;
        line-height: 1.6;
    }

    .cta-button {
        background-color: white;
        color: #1a73e8;
        border: none;
        padding: 12px 24px;
        font-size: 1.1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        background-color: #f1f3f4;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .features {
        padding: 4rem 5rem;
        background-color: #f8f9fa;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .section-title {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 3rem;
        color:rgb(19, 57, 105); 
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        color: #1a73e8;
    }
    
    .feature-card h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: #202124;
    }
    
    .feature-card p {
        color: #5f6368;
        line-height: 1.6;
    }
    .features {
        padding: 6rem 1rem 4rem; /* Más padding arriba para la curva */
        background-color: #f8f9fa;
        margin-top: -40px; /* Solapa con la curva */
        position: relative;
        z-index: 1;
    }
    .separador{
        margin-top: 7rem;
    }
</style>

<main class="hero">
    <h1>Simple, eficiente, cómodo</h1>
    <p>Bienvenido a TickTask, la aplicación que te ayuda a organizar las tareas de tu equipo de manera más eficiente y productiva.</p>
    <button class="cta-button">Comenzar ahora</button>
</main>
<section class="features">
    <div class="container">
        <h2 class="section-title">Revoluciona tu gestión de tareas</h2>

        <div class="features-grid">
            
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Optimiza tus procesos</h3>
                <p>Simplifica la ejecución de actividades cotidianas con flujos inteligentes que maximizan tu rendimiento.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <h3>Trabajo fluido en equipo </h3>
                <p>Coordina perfectamente las subtareas entre colaboradores para alcanzar objetivos compartidos con mayor agilidad.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3>Control detallado</h3>
                <p>Distribuye, monitorea y ajusta subtareas en tiempo real para mantener el proyecto siempre encarrilado.</p>
            </div>
        </div>
        </div>
    </div>
    <div class="separador">

    </div>
</section>

<?= view('layouts/footer') ?>