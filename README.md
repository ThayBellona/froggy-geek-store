# 🐸 Froggy Geek Store

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-F5788D?style=for-the-badge&logo=chartdotjs&logoColor=white)

> **"Moda Geek com Estilo e Conforto"**

A **Froggy Geek** é uma plataforma de e-commerce robusta desenvolvida nativamente em PHP, seguindo o padrão de arquitetura **MVC (Model-View-Controller)**. O projeto simula uma loja virtual de vestuário geek com funcionalidades avançadas de gestão, gamificação e análise de dados.

## 👩‍💻 Sobre o Projeto

Este projeto foi desenvolvido para demonstrar domínio em desenvolvimento Full-Stack sem a dependência de frameworks pesados, focando na performance, segurança e organização de código.

* **Desenvolvimento & Tech Lead:** Thay Bellona 💻
* **Design & Direção Criativa:** Anna Júlia 🎨

## ✨ Funcionalidades Principais

### 🛒 Experiência do Cliente (Front-Office)
* **Sistema de Gamificação:** "Roleta de Cupons" após a compra (chance de ganhar descontos).
* **Carteira Digital Visual:** Cadastro de cartões de crédito com visualização interativa (Flip Card) e tokenização simulada.
* **Checkout Transparente:** Processo de compra fluido com cálculo de descontos e validação de estoque por tamanho.
* **Perfil do Usuário:** Histórico de pedidos, gestão de dados pessoais e sistema de **Tickets de Suporte** (Helpdesk) integrado.
* **Prova Social:** Sistema de avaliações com estrelas e comentários nos produtos.

### 📊 Gestão Administrativa (Back-Office / ERP)
* **Dashboard Analítico (BI):** Gráficos interativos com **Chart.js** para análise de:
    * Fluxo de Caixa Mensal vs Volume de Pedidos.
    * Demografia (Idade Média e Gênero do público).
    * Ranking de Produtos e Cupons mais utilizados.
    * Métricas de Fidelidade (Retenção de clientes).
* **Gestão de Catálogo:** CRUD completo de produtos com controle de estoque específico por grade (P, M, G, GG) e promoções.
* **Gestão de Vendas:** Controle de status de pedidos (Pendente -> Aprovado -> Em Separação -> Entregue).
* **CRM:** Gestão de clientes e atendimento a chamados de suporte/reembolso.

## 🛠️ Tecnologias & Arquitetura

* **Linguagem:** PHP 8+ (Estruturado em MVC: `Models`, `Visoes`, `Controle`, `DAOs`).
* **Banco de Dados:** MySQL (Relacional, com Foreign Keys e Triggers lógicas).
* **Frontend:** HTML5, CSS3 (Customizado), Bootstrap 5, JavaScript (Vanilla + Chart.js).
* **Segurança:**
    * Hash de senhas com `Bcrypt` (password_verify).
    * Prevenção contra SQL Injection (PDO Prepared Statements).
    * Sessões seguras e validação de acesso por nível (Admin/Cliente).

## 🚀 Como Rodar o Projeto

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/SEU-USUARIO/froggy-geek-ecommerce.git](https://github.com/SEU-USUARIO/froggy-geek-ecommerce.git)
    ```
2.  **Configure o Banco de Dados:**
    * Crie um banco de dados chamado `froggygeek_db` no seu MySQL/MariaDB.
    * Importe o arquivo `database/froggygeek_db.sql` (disponível na raiz).
3.  **Conexão:**
    * Verifique o arquivo `conexao/Conexao.php` e ajuste as credenciais (usuário/senha) se necessário.
4.  **Execute:**
    * Inicie o servidor (Apache/XAMPP) e acesse `localhost/froggy-geek-ecommerce`.

## 📸 Screenshots

*(Espaço reservado para prints do Dashboard, Checkout e Perfil)*

---
Desenvolvido com Anna Julia e Código por **Thay Bellona**.
