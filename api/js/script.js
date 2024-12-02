// Biblioteca de Animação
AOS.init();

const menuToggle = document.getElementById('menuToggle');
const headerButtons = document.querySelector('.header-buttons');

menuToggle.addEventListener('click', () => {
    headerButtons.classList.toggle('active');
});

// Valor do frete
document.getElementById('freightForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Pegando os valores do formulário
    const weight = parseFloat(document.getElementById('weight').value);
    const pickupAddress = document.getElementById('pickupAddress').value;
    const deliveryAddress = document.getElementById('deliveryAddress').value;
    const height = parseFloat(document.getElementById('height').value) || 0;
    const width = parseFloat(document.getElementById('width').value) || 0;
    const length = parseFloat(document.getElementById('length').value) || 0;

    // Configurando o serviço DistanceMatrix da Google Maps API
    const service = new google.maps.DistanceMatrixService();

    service.getDistanceMatrix(
        {
            origins: [pickupAddress],
            destinations: [deliveryAddress],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
        },
        function (response, status) {
            if (status !== 'OK') {
                document.getElementById('result').innerHTML = `Erro ao calcular a distância: ${status}`;
            } else {
                const distance = response.rows[0].elements[0].distance.value / 1000; // Distância em km

                // Agora busca as configurações de frete e faz o cálculo
                obterConfiguracoesFrete(distance, weight, height, width, length, pickupAddress, deliveryAddress);
            }
        }
    );
});

// Função para buscar configurações e calcular o frete
function obterConfiguracoesFrete(distance, weight, height, width, length, pickupAddress, deliveryAddress) {
    fetch('fetch_shipping_settings.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const config = data.data;
                calcularFrete(distance, weight, height, width, length, pickupAddress, deliveryAddress, config);
            } else {
                document.getElementById('result').innerHTML = `Erro ao buscar configurações: ${data.message}`;
            }
        })
        .catch(error => {
            console.error('Erro ao buscar configurações de frete:', error);
            document.getElementById('result').innerHTML = 'Erro ao conectar com o servidor.';
        });
}

// Função para calcular o frete
function calcularFrete(distance, weight, height, width, length, pickupAddress, deliveryAddress, config) {
    const volume = (height * width * length) / 1000000; // Volume em m³
    const baseFare = parseFloat(config.tarifa_base);
    const ratePerKg = parseFloat(config.taxa_kg);
    const ratePerM3 = parseFloat(config.taxa_m3);
    const ratePerKm = parseFloat(config.taxa_km);

    let cost = baseFare + (weight * ratePerKg) + (distance * ratePerKm);
    if (volume > 0) {
        cost += (volume * ratePerM3);
    }

    exibirResultadoFrete(pickupAddress, deliveryAddress, distance, weight, cost);
}

// Função para exibir o resultado
function exibirResultadoFrete(enderecoColeta, enderecoEntrega, distancia, peso, valorFrete) {
    document.getElementById("enderecoColeta").textContent = enderecoColeta;
    document.getElementById("enderecoEntrega").textContent = enderecoEntrega;
    document.getElementById("distancia").textContent = distancia.toFixed(2);
    document.getElementById("pesoCarga").textContent = peso.toFixed(2);
    document.getElementById("valorFrete").textContent = valorFrete.toFixed(2);

    document.getElementById("resultadoFrete").style.display = "block";

    // Adicionando a funcionalidade de enviar orçamento via WhatsApp
    document.getElementById("enviarOrcamento").addEventListener("click", function() {
        const mensagem = `Orçamento de Frete:
        Endereço de Coleta: ${enderecoColeta}
        Endereço de Entrega: ${enderecoEntrega}
        Distância: ${distancia.toFixed(2)} km
        Peso da Carga: ${peso} kg
        Valor do Frete: R$ ${valorFrete.toFixed(2)}`;
        
        const numeroWhatsApp = "041999905296"; // Número desejado
        const urlWhatsApp = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${encodeURIComponent(mensagem)}`;
        
        window.open(urlWhatsApp, "_blank"); // Abre o WhatsApp com a mensagem
    });

    // Adicionando a funcionalidade de salvar o orçamento como PDF
    document.getElementById("salvarPdf").addEventListener("click", function() {
    const { jsPDF } = window.jspdf; // Acesso correto ao jsPDF

    const doc = new jsPDF();

    doc.setFontSize(16);
    doc.text("BPA EXPRESS - Orçamento de Frete", 10, 10); // Título do PDF

    doc.setFontSize(12);
    doc.text(`Endereço de Coleta: ${enderecoColeta}`, 10, 20);
    doc.text(`Endereço de Entrega: ${enderecoEntrega}`, 10, 30);
    doc.text(`Distância: ${distancia.toFixed(2)} km`, 10, 40);
    doc.text(`Peso da Carga: ${peso} kg`, 10, 50);
    doc.text(`Valor do Frete: R$ ${valorFrete.toFixed(2)}`, 10, 60);

    doc.save("orcamento_frete.pdf"); // Salva o PDF com o nome "orcamento_frete.pdf"
});
}















