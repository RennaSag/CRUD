<?php
session_start();

// autoload das classes
spl_autoload_register(function ($class) {
    $directories = [
        'models/',
        'controllers/',
        'config/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// verifica se eh uma requisicao da API
if (isset($_GET['api'])) {
    require_once 'api.php';
    exit;
}
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema CRUD - Clientes</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d1117;
            color: #e6edf3;
        }
        
        .container-fluid {
            background: linear-gradient(135deg, #161b22 0%, #21262d 50%, #0d1117 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .card {
            background-color: #21262d;
            border: 1px solid #30363d;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border-radius: 15px;
        }
        
        .card-header {
            background: linear-gradient(45deg,rgb(37, 109, 197),rgb(132, 203, 10)) !important;
            border-bottom: 1px solid #30363d;
            border-radius: 15px 15px 0 0 !important;
        }
        
        .btn-gradient {
            background: linear-gradient(45deg, #238636, #2ea043);
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(45deg, #2ea043, #238636);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(35,134,54,0.4);
            color: white;
        }
        
        .table-dark th {
            background: linear-gradient(45deg,rgb(31, 28, 34),rgb(29, 32, 37));
            border-color: #30363d;
            color:rgb(85, 255, 0);
        }
        
        .table-dark td {
            border-color: #30363d;
            background-color: #0d1117;
        }
        
        .table-dark tbody tr:hover {
            background-color: #161b22;
        }
        
        .modal-content {
            background-color: #21262d;
            border: 1px solid #30363d;
        }
        
        .modal-header {
            background: linear-gradient(45deg, #238636, #2ea043);
            color: white;
            border-bottom: 1px solid #30363d;
            border-radius: 15px 15px 0 0;
        }
        
        .form-control {
            background-color:rgb(15, 23, 13);
            border: 1px solid #30363d;
            color: #e6edf3;
        }
        
        .form-control:focus {
            background-color: #0d1117;
            border-color: #238636;
            box-shadow: 0 0 0 0.2rem rgba(35,134,54,0.25);
            color: #e6edf3;
        }
        
        .form-label {
            color: #e6edf3;
        }
        
        .alert-success {
            background-color: #1f2937;
            border-color: #238636;
            color: #4ade80;
        }
        
        .alert-danger {
            background-color: #1f1f23;
            border-color: #da3633;
            color: #f87171;
        }
        
        .alert-warning {
            background-color: #1f1e17;
            border-color: #fb8500;
            color: #fbbf24;
        }
        
        .btn-primary {
            background-color: #238636;
            border-color: #238636;
        }
        
        .btn-primary:hover {
            background-color: #2ea043;
            border-color: #2ea043;
        }
        
        .btn-danger {
            background-color: #da3633;
            border-color: #da3633;
        }
        
        .btn-danger:hover {
            background-color: #f85149;
            border-color: #f85149;
        }
        
        .btn-secondary {
            background-color: #30363d;
            border-color: #30363d;
            color: #e6edf3;
        }
        
        .btn-secondary:hover {
            background-color: #484f58;
            border-color: #484f58;
            color: #e6edf3;
        }
        
        .text-primary {
            color: #58a6ff !important;
        }
        
        .text-muted {
            color: #7d8590 !important;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0d1117;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #30363d;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #484f58;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card animate-fade-in">
                    <div class="card-header text-white text-center py-4">
                        <h2><i class="fas fa-users me-3"></i>Sistema de Gerenciamento de Clientes</h2>
                        <p class="mb-0">CRUD utilizando PHP OOP, Bootstrap e AJAX</p>
                    </div>
                    <div class="card-body p-4">
                        <div id="alertContainer"></div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="text-primary"><i class="fas fa-list me-2"></i>Lista de Clientes</h4>
                            <button class="btn btn-gradient btn-primary" data-bs-toggle="modal" data-bs-target="#clienteModal" onclick="novoCliente()">
                                <i class="fas fa-plus me-2"></i>Novo Cliente
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-dark table-hover table-striped" id="clientesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Endereço</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="clientesTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="clienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-user-plus me-2"></i>Novo Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="clienteForm">
                        <input type="hidden" id="clienteId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone *</label>
                                <input type="tel" class="form-control" id="telefone" name="telefone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço Completo *</label>
                            <textarea class="form-control" id="endereco" name="endereco" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-gradient btn-primary" onclick="salvarCliente()">
                        <i class="fas fa-save me-2"></i>Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

   
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-trash me-2"></i>Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir este cliente?</p>
                    <p class="text-muted">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let clienteAtual = null;
        let deleteId = null;

        // carrega clientes ao carregar a pagina
        document.addEventListener('DOMContentLoaded', function() {
            carregarClientes();
        });

        // funcao p carregar clientes
        function carregarClientes() {
            fetch('?api=1', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('clientesTableBody');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">Nenhum cliente cadastrado</td></tr>';
                    return;
                }
                
                data.forEach(cliente => {
                    const row = `
                        <tr>
                            <td>${cliente.id}</td>
                            <td>${cliente.nome}</td>
                            <td>${cliente.email}</td>
                            <td>${cliente.telefone}</td>
                            <td>${cliente.endereco}</td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" onclick="editarCliente(${cliente.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmarExclusao(${cliente.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarAlerta('Erro ao carregar clientes', 'danger');
            });
        }

        // funcao p novo cliente
        function novoCliente() {
            clienteAtual = null;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>Novo Cliente';
            document.getElementById('clienteForm').reset();
            document.getElementById('clienteId').value = '';
        }

        // funcao p editar cliente
        function editarCliente(id) {
            fetch(`?api=1&id=${id}`)
            .then(response => response.json())
            .then(cliente => {
                if (cliente.id) {
                    clienteAtual = cliente;
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i>Editar Cliente';
                    document.getElementById('clienteId').value = cliente.id;
                    document.getElementById('nome').value = cliente.nome;
                    document.getElementById('email').value = cliente.email;
                    document.getElementById('telefone').value = cliente.telefone;
                    document.getElementById('endereco').value = cliente.endereco;
                    
                    new bootstrap.Modal(document.getElementById('clienteModal')).show();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarAlerta('Erro ao carregar dados do cliente', 'danger');
            });
        }

        // funcao p salvar cliente
        function salvarCliente() {
            const form = document.getElementById('clienteForm');
            const formData = new FormData(form);
            
            const cliente = {
                nome: formData.get('nome'),
                email: formData.get('email'),
                telefone: formData.get('telefone'),
                endereco: formData.get('endereco')
            };

            // validacao basica
            if (!cliente.nome || !cliente.email || !cliente.telefone || !cliente.endereco) {
                mostrarAlerta('Todos os campos obrigatórios devem ser preenchidos', 'warning');
                return;
            }

            const id = document.getElementById('clienteId').value;
            const url = id ? `?api=1&id=${id}` : '?api=1';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cliente)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    mostrarAlerta(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('clienteModal')).hide();
                    carregarClientes();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarAlerta('Erro ao salvar cliente', 'danger');
            });
        }

        // funcao p confirmar exclusao
        function confirmarExclusao(id) {
            deleteId = id;
            new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
        }

        // Event listener p confirmar exclusao
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                fetch(`?api=1&id=${deleteId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        mostrarAlerta(data.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
                        carregarClientes();
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    mostrarAlerta('Erro ao excluir cliente', 'danger');
                });
            }
        });

        // funcao p mostrar alertas
        function mostrarAlerta(mensagem, tipo) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = `
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                    ${mensagem}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            alertContainer.innerHTML = alert;
            
            // auto remover apos 5s
            setTimeout(() => {
                const alertElement = alertContainer.querySelector('.alert');
                if (alertElement) {
                    alertElement.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>