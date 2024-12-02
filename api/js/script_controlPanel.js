//  Destacar Página Ativa no Menu Lateral
// Pega todos os links do menu
const menuLinks = document.querySelectorAll('.sidebar ul li a');

// Função para adicionar a classe 'active' ao link correto
menuLinks.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});

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


// Dashboard Geral

// Inicializa os gráficos quando o DOM estiver totalmente carregado
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("dashboard").style.display = "none";
    initCharts(); // Chama a função para inicializar os gráficos
});

// Função para inicializar os gráficos
function initCharts() {
    console.log('Chart.js carregado:', typeof Chart !== 'undefined');

    // Faz a requisição para buscar dados do backend
    fetch('get_dashboard_data.php') // Endpoint PHP que retorna os dados
        .then(response => response.json())
        .then(data => {
            const meses = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];
            // Inicializa os gráficos com os dados do backend
            initFreteChart(meses, data.pedidosPorMes);
            initClientesChart(meses, data.clientesPorMes);
        })
        .catch(error => console.error('Erro ao carregar os dados do dashboard:', error));
}

// Função para inicializar o gráfico de pedidos de frete
function initFreteChart(meses, pedidosPorMes) {
    const ctxFrete = document.getElementById('freteChart').getContext('2d');
    new Chart(ctxFrete, {
        type: 'pie',
        data: {
            labels: meses, // Exibe todos os meses
            datasets: [{
                label: 'Pedidos de Frete',
                data: pedidosPorMes, // Dados alinhados com os meses
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(201, 203, 207, 0.6)',
                    'rgba(123, 239, 178, 0.6)',
                    'rgba(250, 128, 114, 0.6)',
                    'rgba(60, 179, 113, 0.6)',
                    'rgba(210, 180, 140, 0.6)',
                    'rgba(138, 43, 226, 0.6)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Pedidos de Fretes',
                    font: { size: 18 },
                    color: '#333',
                    padding: { bottom: 60 } 
                },
                legend: { position: 'right' },
                tooltip: { enabled: true }
            }
        }
    });
}

// Função para inicializar o gráfico de novos clientes
function initClientesChart(meses, clientesPorMes) {
    const ctxClientes = document.getElementById('clientesChart').getContext('2d');
    new Chart(ctxClientes, {
        type: 'bar',
        data: {
            labels: meses, // Exibe todos os meses
            datasets: [{
                label: 'Novos Clientes',
                data: clientesPorMes, // Dados alinhados com os meses
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Novos Clientes',
                    font: { size: 18 },
                    color: '#333',
                    padding: { bottom: 20 }
                },
                legend: { position: 'top' },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: { size: 16 },
                    bodyFont: { size: 14 }
                }
            }
        }
    });
}

// Gestão de pedidos de fretes
let currentPage = 1;
const rowsPerPage = 10; // 10 pedidos por página
let originalData = []; // Dados originais carregados do servidor
let filteredData = []; // Dados filtrados pela pesquisa

// Função para carregar dados do servidor
async function fetchOrders() {
    try {
        const response = await fetch('list_orders.php'); // Carrega todos os pedidos
        if (!response.ok) throw new Error('Erro ao buscar pedidos');
        const data = await response.json();
        originalData = data.pedidos;
        filteredData = [...originalData]; // Inicialmente, sem filtro
        displayPage(1); // Exibe a primeira página
    } catch (error) {
        console.error('Erro:', error.message);
    }
}

// Função para exibir os dados na tabela com paginação
function loadTableData(data) {
    const tbody = document.querySelector("#pedidosTable tbody");
    tbody.innerHTML = ""; // Limpa a tabela

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    const paginatedData = data.slice(start, end); // Obtém os dados da página atual

    paginatedData.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.cliente}</td>
            <td>${item.origem}</td>
            <td>${item.destino}</td>
            <td>${item.valor}</td>
            <td>${item.data}</td>
            <td>${item.status}</td>
            <td>
                <button class="btn-acao editar" onclick="showModal(${item.id}, '${item.status}')"><i class="fas fa-edit"></i> Editar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    updatePaginationControls(data.length); // Atualiza os controles de paginação
}

// Função para atualizar os controles de paginação
function updatePaginationControls(totalRows) {
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    document.getElementById("pageNumber").innerText = `Página ${currentPage} de ${totalPages}`;
    document.getElementById("prevButton").disabled = currentPage === 1;
    document.getElementById("nextButton").disabled = currentPage === totalPages;
}

// Funções para mudar de página
function displayPage(page) {
    currentPage = page;
    loadTableData(filteredData); // Usa os dados filtrados
}

function nextPage() {
    if (currentPage < Math.ceil(filteredData.length / rowsPerPage)) {
        currentPage++;
        displayPage(currentPage);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
    }
}

// Função para filtrar a tabela
function filterTable() {
    const filter = document.getElementById("searchInput").value.toLowerCase();
    filteredData = originalData.filter(item =>
        Object.values(item).some(value => value.toString().toLowerCase().includes(filter))
    );
    currentPage = 1; // Redefine para a primeira página
    displayPage(currentPage);
}

// Função para exibir o popup modal de edição de status
function showModal(orderId, currentStatus) {
    const modal = document.getElementById("statusModal");
    document.getElementById("orderId").value = orderId; // Atribui o id do pedido
    document.getElementById("newStatus").value = currentStatus; // Atribui o status atual
    modal.classList.add("show"); // Exibe o modal como popup
}

// Função para fechar o popup modal
function closeModal() {
    const modal = document.getElementById("statusModal");
    modal.classList.remove("show"); // Remove a classe 'show' para esconder o modal
}

// Função para atualizar o status
async function updateStatus(event) {
    event.preventDefault(); 

    const orderId = document.getElementById("orderId").value.trim(); 
    const newStatus = document.getElementById("newStatus").value.trim();

    try {
        const response = await fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_frete: orderId, novo_status: newStatus })
        });

        if (!response.ok) throw new Error('Erro ao atualizar status');
        const result = await response.json();

        if (result.success) {
            alert(result.message);
            fetchOrders(); // Recarrega os pedidos após a atualização
            closeModal(); // Fecha o modal após a atualização
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Erro:', error.message);
        alert('Erro ao atualizar status. Verifique os logs.');
    }
}
// Inicializa a tabela e exibe a primeira página
document.addEventListener("DOMContentLoaded", () => fetchOrders());


// Gestão de clientes
let currentClientePage = 1;
const rowsPerPageClientes = 10;

// Função para filtrar a tabela de clientes
function filterClientesTable() {
    const filter = document.getElementById("searchClienteInput").value.toLowerCase();
    const rows = document.querySelectorAll("#clientesTable tbody tr");
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
    });
    displayClientePage(currentClientePage); // Recalcula a paginação após o filtro
}

// Função para ordenar a tabela de clientes
function sortClientesTable(columnIndex) {
    const table = document.getElementById("clientesTable");
    const rows = Array.from(table.rows).slice(1);
    const isAscending = table.rows[0].cells[columnIndex].classList.toggle("asc");

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText;
        const cellB = b.cells[columnIndex].innerText;
        return isAscending
            ? cellA.localeCompare(cellB)
            : cellB.localeCompare(cellA);
    });

    rows.forEach(row => table.appendChild(row));
}

// Funções para paginação da tabela de clientes
function displayClientePage(page) {
    const rows = Array.from(document.querySelectorAll("#clientesTable tbody tr"));
    const filteredRows = rows.filter(row => row.style.display !== "none"); // Filtra as linhas visíveis
    const totalPages = Math.ceil(filteredRows.length / rowsPerPageClientes);

    currentClientePage = Math.max(1, Math.min(page, totalPages));
    
    rows.forEach((row, index) => {
        row.style.display = index >= (currentClientePage - 1) * rowsPerPageClientes && index < currentClientePage * rowsPerPageClientes ? "" : "none";
    });

    document.getElementById("clientePageNumber").innerText = currentClientePage;
}

function nextClientePage() {
    displayClientePage(currentClientePage + 1);
}

function prevClientePage() {
    displayClientePage(currentClientePage - 1);
}

// Inicializa a exibição da primeira página ao carregar
document.addEventListener("DOMContentLoaded", () => displayClientePage(1));

// Função para carregar dados de clientes
function loadClientesData(data) {
    const tbody = document.querySelector("#clientesTable tbody");
    tbody.innerHTML = ""; // Limpa a tabela
    data.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.nome_cliente}</td> <!-- Nome do Cliente -->
            <td>${item.email_cliente}</td> <!-- Email do Cliente -->
            <td>${item.telefone_cliente}</td> <!-- Telefone do Cliente -->
            <td>${item.razao_social || 'N/A'}</td> <!-- Razão Social -->
            <td>${new Date(item.data_cadastro).toLocaleDateString()}</td> <!-- Data de Cadastro -->
            <td>${item.status_cliente}</td> <!-- Status -->
            <td>
                <button class="btn-acao editar" onclick="showClienteModal(${item.id_cliente}, '${item.status_cliente}')">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
    displayClientePage(1); // Exibe a primeira página
}


// Função para exibir o modal de edição de status
function showClienteModal(clienteId, currentStatus) {
    const modal = document.getElementById("clienteModal");
    document.getElementById("clienteId").value = clienteId; // Atribui o ID do cliente
    document.getElementById("clienteStatus").value = currentStatus; // Atribui o status atual
    modal.classList.add("show"); // Exibe o modal
}

// Função para fechar o modal de edição
function closeClienteModal() {
    const modal = document.getElementById("clienteModal");
    modal.classList.remove("show");
}

// Função para atualizar o status do cliente
async function updateClienteStatus(event) {
    event.preventDefault(); // Previne o comportamento padrão do formulário

    const clienteId = document.getElementById("clienteId").value;
    const newStatus = document.getElementById("clienteStatus").value;

    try {
        const response = await fetch('update_clientes_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: clienteId, status: newStatus })
        });

        if (!response.ok) throw new Error('Erro ao atualizar status do cliente');
        alert('Status atualizado com sucesso');
        fetchClientes(); // Recarrega os clientes após a atualização
        closeClienteModal(); // Fecha o modal após a atualização
    } catch (error) {
        console.error('Erro:', error.message);
        alert('Erro ao atualizar status do cliente. Verifique os logs.');
    }
}

// Função para buscar e carregar os dados de clientes
async function fetchClientes() {
    try {
        const response = await fetch('get_clientes.php');
        const data = await response.json();

        if (data.success === false) {
            throw new Error(data.message);
        }

        loadClientesData(data); // Carrega os dados na tabela
    } catch (error) {
        console.error('Erro ao carregar clientes:', error);
        alert('Erro ao carregar clientes. Verifique os logs.');
    }
}

// Chame a função para carregar os dados ao carregar a página
document.addEventListener("DOMContentLoaded", fetchClientes);


// Configurações de Frete
function salvarConfiguracoesFrete() {
    const tarifaBase = document.getElementById("tarifa").value;
    const taxaPorKm = document.getElementById("taxa").value;
    const taxaKg = document.getElementById("taxaKg").value;
    const taxaVolume = document.getElementById("taxaVolume").value;

    const dados = {
        tarifa_base: tarifaBase,
        taxa_km: taxaPorKm,
        taxa_kg: taxaKg,
        taxa_m3: taxaVolume
    };

    fetch('save_shipping_settings.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(dados),
    })
    .then(response => response.json())
    .then(data => {
        const mensagem = document.getElementById("mensagemConfirmacao");
        if (data.success) {
            mensagem.textContent = data.message;
            mensagem.style.color = 'green';
        } else {
            mensagem.textContent = data.message;
            mensagem.style.color = 'red';
        }
        mensagem.style.display = "block";
        setTimeout(() => mensagem.style.display = "none", 3000);
    })
    .catch(() => {
        const mensagem = document.getElementById("mensagemConfirmacao");
        mensagem.textContent = 'Erro ao salvar configurações. Tente novamente.';
        mensagem.style.color = 'red';
        mensagem.style.display = "block";
        setTimeout(() => mensagem.style.display = "none", 3000);
    });
}



