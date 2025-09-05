
# Networking Profissional
![PHP](https://img.shields.io/badge/PHP-8.0+-blue)
![Status](https://img.shields.io/badge/status-Em%20Desenvolvimento-yellow)

Uma plataforma de networking profissional desenvolvida em PHP puro, permitindo que profissionais se conectem, compartilhem experiências e encontrem oportunidades de forma prática e segura.

---

## Índice
1. [Sobre](#sobre)
2. [Pré-requisitos](#pré-requisitos)
3. [Instalação Rápida](#instalação-rápida)
4. [Credenciais de Teste](#credenciais-de-teste)
5. [Funcionalidades](#funcionalidades)
6. [Segurança](#segurança)
7. [Estrutura do Projeto](#estrutura-do-projeto)
8. [Banco de Dados](#banco-de-dados)
9. [Personalização](#personalização)
10. [Solução de Problemas](#solução-de-problemas)
11. [Equipe](#equipe)



---

## Sobre
O **Networking Profissional** é uma aplicação simples e robusta, que tem como objetivo aproximar profissionais e empresas.  
Com ela, você pode criar um perfil, publicar conteúdos, interagir em um feed e indicar sua situação profissional através de status personalizados.

---

## Pré-requisitos
Antes de começar, certifique-se de ter os seguintes itens instalados:

- PHP **8.0 ou superior**
- MySQL **5.7 ou superior**
- Extensões habilitadas no PHP:
  - `PDO`
  - `mbstring`
  - `session`
- Servidor local (como XAMPP, Laragon, WAMP, etc.)
- Navegador atualizado (Google Chrome, Firefox, etc.)

---

## Instalação Rápida
1. **Clone este repositório:**
   ```bash
   git clone https://github.com/seuusuario/Projeto-Plataforma-Historias-Profissionais.git
   ```

2. **Acesse a pasta do projeto:**
   ```bash
   cd Projeto-Plataforma-Historias-Profissionais
   ```

3. **Configure as credenciais do banco de dados no arquivo:**
   ```
   config/database.php
   ```

4. **Execute o script de configuração:**
   ```bash
   php setup_database.php
   ```

5. **Inicie o servidor PHP embutido:**
   ```bash
   php -S localhost:8000
   ```

6. **Acesse o sistema no navegador:**
   ```
   http://localhost:8000
   ```

---

## Credenciais de Teste
Estas contas são criadas automaticamente pelo `setup_database.php` para demonstração inicial:

| Email           | Senha    | Status                    |
|-----------------|----------|---------------------------|
| joao@email.com  | password | Disponível para contato   |
| maria@email.com | password | Recrutadora               |
| pedro@email.com | password | Procurando oportunidades  |

---

## Funcionalidades
- ✅ **Cadastro e login de usuários** – Criação de conta com autenticação segura.  
- ✅ **Feed interativo** – Publicações com curtidas e comentários.  
- ✅ **Perfis editáveis** – Atualize foto, bio e status profissional.  
- ✅ **Sistema de status** – Indique sua disponibilidade no mercado.  
- ✅ **Interface responsiva** – Totalmente adaptável para desktop e mobile.  
- ✅ **Segurança robusta** – Proteções contra SQL Injection e XSS.

---

## Segurança
O projeto segue boas práticas de segurança em PHP:

- Senhas criptografadas com `password_hash()`.
- Proteção contra SQL Injection com **prepared statements**.
- Sanitização de dados com `htmlspecialchars()`.
- Sessões seguras com regeneração de ID (`session_regenerate_id()`).
- Validação rigorosa de entradas em todos os formulários.

---

## Estrutura do Projeto
```
Projeto-Plataforma-Historias-Profissionais/
├── actions/ # Processamento de ações
├── auth/ # Sistema de autenticação
├── config/ # Configurações
├── includes/ # Arquivos incluídos
├── feed.php # Página principal
├── index.php # Página inicial
├── inscricao.html # Registro
├── login.html # Login
├── perfil.php # Perfil
├── setup_database.php # Configuração automática
├── security_check.php # Verificação de segurança
├── style.css # Estilos
├── README.md # Documentação
└── CHANGELOG.md # Registro de mudanças
```
---

## Banco de Dados

### Estrutura das tabelas:
- **`users`** – Armazena informações de login e perfil.  
- **`posts`** – Publicações feitas pelos usuários.  
- **`likes`** – Controle de curtidas nos posts.  
- **`comments`** – Comentários em postagens.

### Configuração padrão:
| Configuração | Valor               |
|--------------|---------------------|
| Host         | localhost           |
| Usuário      | root                |
| Senha        | (vazia)             |
| Banco        | networking_platform |

> Dica: Gere o banco automaticamente executando `php setup_database.php`.

---

## Personalização

### Status Profissionais Disponíveis:
- `disponivel_contato` — Disponível para contato  
- `procurando_oportunidades` — Procurando oportunidades  
- `recrutador` — Recrutador  
- `empregado` — Empregado  

### Cores e Estilos:
Edite o arquivo `style.css` para personalizar:
- Paleta de cores
- Tipografia
- Layout responsivo

---

## Solução de Problemas

### 1. Erro de conexão com o banco de dados
- Verifique se o MySQL está rodando.
- Confirme as credenciais em `config/database.php`.
- Rode novamente o script `setup_database.php`.

### 2. Páginas não carregam
- Certifique-se de que o PHP está instalado e atualizado.
- Verifique se a extensão `PDO` está habilitada.
- Consulte os logs de erro do servidor.

### 3. Problemas com sessão
- Confirme se o PHP tem permissão para salvar sessões.
- Garanta que `session_start()` esteja presente nos arquivos principais.

---

## Equipe

**Grupo 12**  
Este projeto foi desenvolvido pelo Grupo 12 como parte da disciplina Projeto Integrador: Desenvolvimento Orientado a Dispositivos Móveis e Baseados na Web, no Senac.

- Henrique Dias Van Rossum da Silva  
- Jadson dos Santos Machado  
- Erasmo Eloi da Hora Neto  
- Lara Eugenia Campello da Silva Moreira  
- Ana Quezia Flores Costa e Silva  
- Guilherme Ramos de Oliveira

