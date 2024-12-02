// visibilidade do botão toggle em dispositivos móveis
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    sidebar.classList.toggle("show");
    
    if (window.innerWidth > 768) {
        mainContent.classList.toggle("shift");
    }
}

// Fecha o menu ao clicar em um link, apenas em telas menores
document.querySelectorAll('.sidebar ul li a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            toggleSidebar();
        }
    });
});

// Simular o frete
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

                // Buscar as configurações de frete e fazer o cálculo
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
}

    //  Destacar Página Ativa no Menu Lateral
    // Pega todos os links do menu
    const menuLinks = document.querySelectorAll('.sidebar ul li a');

    // Função para adicionar a classe 'active' ao link correto
    menuLinks.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});

// Limpar o formulário e exibir uma mensagem após Solicitar Frete
document.getElementById('enviarOrcamento').addEventListener('click', function (e) {
    e.preventDefault();

    // Exibir mensagem de sucesso ao lado do título
    const formTitle = document.querySelector('#simular-frete h2');
    if (formTitle) {
        // Evitar múltiplas mensagens duplicadas
        if (!document.querySelector('.success-message')) {
            const message = document.createElement('span');
            message.textContent = 'Seu pedido foi solicitado com sucesso!';
            message.style.color = 'green';
            message.style.marginLeft = '30px';
            message.classList.add('success-message'); // Adiciona uma classe para controle

            formTitle.appendChild(message);

            // Remove a mensagem após 5 segundos
            setTimeout(() => {
                if (formTitle.contains(message)) {
                    formTitle.removeChild(message);
                }
            }, 5000);
        }
    } else {
        console.error('Título do formulário não encontrado.');
    }
});

// Enviar os dados via AJAX para o save_shipping.php
document.getElementById('enviarOrcamento').addEventListener('click', function () {
    // Coletando os dados exibidos no resultado do cálculo
    const pickup = document.getElementById('pickupAddress').value;
    const delivery = document.getElementById('deliveryAddress').value;
    const height = document.getElementById('height').value;
    const width = document.getElementById('width').value;
    const length = document.getElementById('length').value;
    const weight = document.getElementById('weight').value;
    const freightValue = document.getElementById('valorFrete').innerText; // Valor calculado exibido

    // Cria o objeto com os dados a serem enviados
    const freightData = {
        pickup: pickup.trim(),
        delivery: delivery.trim(),
        height: parseFloat(height) || 0,
        width: parseFloat(width) || 0,
        length: parseFloat(length) || 0,
        weight: parseFloat(weight) || 0,
        freightValue: parseFloat(freightValue.replace('R$', '').trim()) || 0,
    };

    // Envia os dados ao PHP via fetch
    fetch('save_shipping.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(freightData),
    })
        // Verifica se a resposta tem o formato correto de JSON   
        .then(response => response.json())
        .then(data => {
            console.log("Resposta do PHP:", data);  // Adicione esta linha para depurar
            if (data.success) {
                alert('Frete solicitado com sucesso!');
                
            } else {
                alert('Erro ao solicitar o frete: ' + data.message);
            }
        })
       
});


// Alternar a visibilidade das seções conforme a escolha no menu lateral.
function showSection(sectionId) {
    // Esconde todas as seções
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.style.display = 'none');

    // Mostra a seção selecionada
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }
}


// Gestão de envios
let originalEnviosData = []; // Armazenar os dados originais
let currentEnviosPage = 1; // Página atual
const itemsPerPage = 10; // Itens por página

// Função para carregar os dados do backend
async function fetchEnviosData() {
    try {
        const response = await fetch('pick_up_freight.php'); // Chamada ao backend
        const data = await response.json(); // Converte a resposta para JSON

        if (data.error) {
            console.error(data.message); // Exibe erro no console
        } else {
            originalEnviosData = data; // Armazena os dados originais
            loadEnviosTableData(data); // Carrega os dados na tabela
        }
    } catch (error) {
        console.error("Erro ao buscar os dados dos fretes:", error);
    }
}

// Função para preencher a tabela com os dados carregados
function loadEnviosTableData(data) {
    const totalPages = Math.ceil(data.length / itemsPerPage); // Total de páginas
    displayEnviosPage(currentEnviosPage); // Exibe a página atual
    updatePagination(totalPages); // Atualiza a navegação de paginação
}

// Função para exibir os itens da página selecionada
function displayEnviosPage(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = page * itemsPerPage;
    const pageData = originalEnviosData.slice(startIndex, endIndex); // Seleciona os itens da página atual

    const tbody = document.querySelector("#enviosTable tbody");
    tbody.innerHTML = ""; // Limpa a tabela

    pageData.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.id_frete}</td>
            <td>${item.endereco_origem}</td>
            <td>${item.endereco_destino}</td>
            <td>${item.valor_frete}</td>
            <td>${item.data}</td>
            <td>${item.status}</td>
            <td>
                <button class="btn-acao editar" onclick="editEnvio(${item.id_frete})"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn-acao cancelar" onclick="cancelarEnvio(${item.id_frete})"><i class="fas fa-ban"></i> Cancelar</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Função para cancelar um pedido
async function cancelarEnvio(id_frete) {
    const confirmation = confirm("Você tem certeza que deseja cancelar este Pedido?");
    if (!confirmation) return;

    try {
        // Enviar requisição ao backend para cancelar o pedido
        const response = await fetch('delete_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ id_frete: id_frete })
        });

        const result = await response.text();
        alert(result); // Exibe o resultado do cancelamento

        // Atualizar os dados localmente
        originalEnviosData = originalEnviosData.map(item => {
            if (item.id_frete === id_frete) {
                item.status = 'Cancelado'; // Atualiza o status do pedido para "Cancelado"
            }
            return item;
        });
        displayEnviosPage(currentEnviosPage); // Atualizar a tabela
    } catch (error) {
        console.error("Erro ao cancelar o envio:", error);
        alert("Erro ao cancelar o envio!");
    }
}

// Editar pedido
function editEnvio(id_frete) {
    // Procurar o envio correspondente no originalEnviosData
    const envio = originalEnviosData.find(item => item.id_frete === id_frete);

    if (envio) {
        // Salvar os dados do envio no localStorage
        localStorage.setItem("envioEditar", JSON.stringify(envio));

        // Mostrar a seção de simulação de frete
        document.getElementById("simular-frete").style.display = "block";

        // Ocultar outras seções
        document.querySelectorAll(".content-section").forEach(section => {
            if (section.id !== "simular-frete") {
                section.style.display = "none";
            }
        });

        // Preencher os campos do formulário
        preencherFormularioSimulacao(envio);
    } else {
        alert("Envio não encontrado.");
    }
}

// Função para preencher o formulário de simulação com os dados existentes
function preencherFormularioSimulacao(envio) {
    document.getElementById("pickupAddress").value = envio.endereco_origem || "";
    document.getElementById("deliveryAddress").value = envio.endereco_destino || "";
    document.getElementById("height").value = envio.altura || "";
    document.getElementById("width").value = envio.largura || "";
    document.getElementById("length").value = envio.comprimento || "";
    document.getElementById("weight").value = envio.peso || "";
}

// Salvar as alterações dos pedidos
document.getElementById("salvarEdicao").addEventListener("click", function () {
    const envioEditar = JSON.parse(localStorage.getItem("envioEditar"));

    if (envioEditar) {
        // Capturar os novos valores do formulário
        const novoEnvio = {
            id_frete: envioEditar.id_frete,
            endereco_origem: document.getElementById("pickupAddress").value,
            endereco_destino: document.getElementById("deliveryAddress").value,
            altura: document.getElementById("height").value,
            largura: document.getElementById("width").value,
            comprimento: document.getElementById("length").value,
            peso: document.getElementById("weight").value,
            valor_frete: document.getElementById("valorFrete").textContent,
            data: envioEditar.data, // Preservar a data original
            status: envioEditar.status, // Preservar o status original
        };

        // Enviar a alteração para o servidor (banco de dados)
        fetch('edit_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(novoEnvio),
        })
        .then(response => response.json())
        .then(data => {
            // Se a atualização no banco for bem-sucedida, atualizar a tabela
            if (data.success) {
                // Atualizar a tabela local com a nova informação
                const index = originalEnviosData.findIndex(item => item.id_frete === envioEditar.id_frete);
                if (index !== -1) {
                    originalEnviosData[index] = novoEnvio;
                }
                
                // Atualizar a tabela na interface
                displayEnviosPage(1);
                
                // Limpar o localStorage
                localStorage.removeItem("envioEditar");

                // Ocultar a seção de simulação e mostrar a tabela novamente
                document.getElementById("simular-frete").style.display = "none";
                document.querySelector("#meus-envios").style.display = "block";
            } else {
                // Mostrar uma mensagem de erro se a atualização falhar
                alert("Erro ao salvar as alterações no banco de dados.");
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert("Erro de comunicação com o servidor.");
        });
    }
});


// Cancelar a edição
document.getElementById("cancelarEdicao").addEventListener("click", function () {
    localStorage.removeItem("envioEditar");
    document.getElementById("simular-frete").style.display = "none";
    document.querySelector("#meus-envios").style.display = "block";
});



// Função para atualizar os controles de paginação
function updatePagination(totalPages) {
    const pageNumber = document.getElementById("enviosPageNumber");
    pageNumber.innerText = currentEnviosPage; // Atualiza o número da página atual

    // Ativar/desativar o botão "Anterior"
    const prevButton = document.querySelector(".pagination-btn:first-child");
    prevButton.disabled = currentEnviosPage === 1;

    // Ativar/desativar o botão "Próxima"
    const nextButton = document.querySelector(".pagination-btn:last-child");
    nextButton.disabled = currentEnviosPage === totalPages;
}

// Função para ir para a próxima página
function nextEnviosPage() {
    const totalPages = Math.ceil(originalEnviosData.length / itemsPerPage);
    if (currentEnviosPage < totalPages) {
        currentEnviosPage++;
        displayEnviosPage(currentEnviosPage); // Exibe os dados da nova página
        updatePagination(totalPages); // Atualiza os controles de paginação
    }
}

// Função para ir para a página anterior
function prevEnviosPage() {
    if (currentEnviosPage > 1) {
        currentEnviosPage--;
        displayEnviosPage(currentEnviosPage); // Exibe os dados da nova página
        updatePagination(Math.ceil(originalEnviosData.length / itemsPerPage)); // Atualiza os controles de paginação
    }
}

// Função para filtrar a tabela
function filterEnviosTable() {
    const filter = document.getElementById("enviosSearchInput").value.toLowerCase();
    
    // Se o filtro estiver vazio, restaura os dados originais e a página atual
    if (filter === "") {
        originalEnviosData = [];  // Limpa os dados filtrados
        fetchEnviosData(); // Recarrega os dados completos
        return;
    }

    // Caso contrário, filtra os dados com base no texto do filtro
    const filteredRows = originalEnviosData.filter(item => {
        return Object.values(item).some(value =>
            String(value).toLowerCase().includes(filter)
        );
    });

    updateEnviosTable(filteredRows);
    displayEnviosPage(1); // Exibe a primeira página após o filtro
}

// Função para atualizar a tabela com as linhas filtradas
function updateEnviosTable(filteredRows) {
    const totalPages = Math.ceil(filteredRows.length / itemsPerPage); // Total de páginas
    originalEnviosData = filteredRows; // Atualiza os dados filtrados
    displayEnviosPage(currentEnviosPage); // Exibe a página atual
    updatePagination(totalPages); // Atualiza os controles de paginação
}

// Inicializa a exibição dos dados e da página ao carregar
document.addEventListener("DOMContentLoaded", () => {
    fetchEnviosData(); // Busca os dados ao carregar
    displayEnviosPage(1); // Inicializa a tabela
});


 // Verifica se a mensagem de cadastro atualizado está visível
 window.onload = function() {
    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        // Define o tempo para desaparecer
        setTimeout(function() {
            alertMessage.style.opacity = 0; // Faz a mensagem desaparecer
            setTimeout(function() {
                alertMessage.style.display = 'none'; // Remove a mensagem do layout após a transição
            }, 500); // Tempo de transição (500ms)
        }, 5000); // Tempo até desaparecer (3000ms = 3 segundos)
    }
}




