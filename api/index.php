<?php
// Iniciar a sessão
session_start();

include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Incluir jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- API google -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARLiv-4fC4XefW7g533SI5Mbwr2hQZYaU"></script>

    <!-- JS -->
    <script src="js/script.js" defer></script>

    <!-- Biblioteca animação -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
     <link rel="stylesheet" href="css/style.css">
    <title>Bpa Express</title>
</head>
<body>
        <!-- Header -->
        <div class="bg-home">
            <header>
                <nav class="header-content container">

                    <div class="header-icons" data-aos="fade-down">
                        <a href="#">
                            <i class="fa-brands fa-instagram fa-2x"></i>
                        </a>
                        <a href="#">
                            <i class="fa-brands fa-facebook fa-2x"></i>
                        </a>
                    </div>
                    <div class="header-logo" data-aos="fade-up" data-aos-delay="350">
                        <img src="assets/default_transparent_765x625.png" alt="Logo BPA EXPRESS" data-aos="flip-up" data-aos-duration="1500" data-aos-delay="400">
                    </div>
                    <div class="header-buttons"  data-aos="fade-down">
                        <a class="header-button" href="login.php">
                            Entrar
                        </a>
                        <a class="header-button" href="register.php">
                            Cadastre-se
                        </a>
                    </div>
                </nav>
                
                <!-- Hero -->
                <main class="hero container" data-aos="fade-up" data-aos-delay="400">
                    <h1>CONECTANDO DESTINOS E ENTREGANDO CONFIANÇA!</h1>
                    <a href="https://api.whatsapp.com/send?phone=041999905296&text=Olá,%20gostaria%20de%20saber%20mais%20sobre%20a%20BPA%20EXPRESS" class="button-contact" target="_blank">
                        Entrar em contato
                    </a>
                </main>

                <!-- Menu toggle -->
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </div>
            </header>
        </div>

    <!-- About -->
        <section class="about">
            <div class="container about-content">
                <div data-aos="zoom-in" data-aos-delay="100">
                    <img src="assets/entregas.png" alt="BPA EXPRESS">
                </div>
                <div class="about-description" data-aos="zoom-out-left" data-aos-delay="250">
                <h2>Sobre</h2>
                <p>A BPA Express é uma empresa de transporte logístico com uma abordagem moderna e eficiente, garantindo agilidade, segurança e pontualidade em todas as entregas.
                    </p><br>
                    <p>Na BPA Express, acreditamos que cada entrega é uma oportunidade de construir relacionamentos sólidos e duradouros. Estamos prontos para transportar suas mercadorias com o máximo cuidado e precisão, para qualquer lugar, a qualquer hora.
                    </p><br>
                    <p>Venha conhecer a BPA Express e descubra como podemos transformar suas necessidades de transporte em experiências positivas e confiáveis.
                    </p>
                </div>
            </div>
        </section>
    <!-- Services -->
        <section class="services">
            <div class="services-content container">
                <h2>Nossos Serviços</h2>
                <p>Na BPA EXPRESS, oferecemos soluções completas de transporte para atender às suas necessidades logísticas. Garantimos eficiência, segurança e agilidade em cada serviço prestado. Nossos serviços:</p>
            </div>
            <section class="our-services">
                <div class="service" data-aos="fade-up" data-aos-delay="150">
                    <img src="assets/coletas.png" alt="Coletas">
                    <div class="service-info">
                        <strong>Coletas</strong>
                    </div>
                </div>
                <div class="service" data-aos="fade-up" data-aos-delay="300">
                    <img src="assets/entregas.png" alt="Entregas">
                    <div class="service-info">
                        <strong>Entregas</strong>
                    </div>
                </div>
                <div class="service" data-aos="fade-up" data-aos-delay="500">
                    <img src="assets/fretes.png" alt="Fretes">
                    <div class="service-info">
                        <strong>Fretes</strong>
                    </div>
                </div>
            </section>
        </section>
 <!-- Freight calculator -->
 <section class="freight-calculator">
        <div class="container">
            <h2>Descubra o valor do seu Frete</h2>
            <form id="freightForm">
                <div class="form-group">
                    <label for="origem">Origem</label>
                    <input type="text" id="pickupAddress" name="pickup" placeholder="Endereço de origem" required>
                </div>
                <div class="form-group">
                    <label for="destino">Destino</label>
                    <input type="text" id="deliveryAddress" name="delivery" placeholder="Endereço de destino" required>
                </div>
                <div class="form-group">
                    <label for="altura">Altura (cm)</label>
                    <input type="number" id="height" placeholder="Altura">
                </div>
                <div class="form-group">
                    <label for="largura">Largura (cm)</label>
                    <input type="number" id="width" placeholder="Largura">
                </div>
                <div class="form-group">
                    <label for="comprimento">Comprimento (cm)</label>
                    <input type="number" id="length" placeholder="Comprimento">
                </div>
                <div class="form-group">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="weight" name="weight" placeholder="Peso da Carga (kg)" required>
                </div>
                
                <button class="btn-calc-freight" type="submit">Calcular</button>
            </form>
            <div id="result" class="result"></div>

            <!-- Result freight -->
            <div class="result-freight" id="resultadoFrete" style="display:none;">
                <h3>Detalhes do Seu Frete</h3>
                <p><strong>Endereço de Coleta:</strong> <span id="enderecoColeta"></span></p>
                <p><strong>Endereço de Entrega:</strong> <span id="enderecoEntrega"></span></p>
                <p><strong>Distância:</strong> <span id="distancia"></span> km</p>
                <p><strong>Peso da Carga:</strong> <span id="pesoCarga"></span> kg</p>
                <p><strong>Valor do Frete:</strong> R$ <span id="valorFrete"></span></p>
                <p>*Para simples conferência, sujeito a alterações*</p>
                <button  class="btn-calc-freight" id="enviarOrcamento" type="submit">Enviar orçamento</button>
                <button  class="btn-calc-freight" id="salvarPdf" type="button">Salvar como PDF</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-icons">
            <a href="#">
                <i class="fa-brands fa-instagram fa-2x"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-facebook fa-2x"></i>
            </a>
        </div>
        <div class="footer-logo">
            <img src="assets/default_transparent_765x625.png" alt="Logo Transportadora">
        </div>
        <p>Copyright 2024 | BPA EXPRESS - Todos direitos reservados.</p>
    </footer>

    <!-- Button whatsapp -->
    <a href="https://api.whatsapp.com/send?phone=041999905296&text=Quero%20fazer%20um%20orçamento!" class="btn-whatsapp" target="_blank" data-aos="zoom-in-up" data-aos-delay="250">
        <img src="assets/whatsapp.png" alt="Botao whatsapp">
        <span class="tooltip-text">Faça seu orçamento</span>
    </a>
</body>
</html>
