const express = require('express');
const stylus = require('express-stylus');
const mailFn = require('node-mailjet');
const port = 3300;
const app = express();
var publicDir = __dirname+ '/public';

app.use(express.static(publicDir));
app.use(stylus({
  src: publicDir
}));

app.use(express.urlencoded({ extended: false }));
app.use(express.json());

// Se indica el motor del plantillas a utilizar
app.set('view engine', 'pug');

app.get('/', (req, res) => {
  res.render('tpl.pug', { title: 'ARKON DATA', message: 'Hello there!', })
});

app.post('/post.php', (req, res) => {
  const api_key = '1c48a16a2a66246cab5916c6b158ae81'
  const secret_key = '2f51bc72ee88f0da76b75fb4270f15d3'

  const SENDER_EMAIL = 'kharman123@gmail.com';
  const RECIPIENT_EMAIL = 'kharman123+5@gmail.com';

  const mailjet = mailFn.apiConnect(
      api_key,
      secret_key
  )

  const frm = req.body

  mailjet.post('send', { version: 'v3.1' }).request({
    Messages: [
      {
        From: {
          Email: SENDER_EMAIL,
          Name: 'Me',
        },
        To: [
          {
            Email: RECIPIENT_EMAIL,
            Name: 'You',
          },
        ],
        Subject: 'NodeJs contact form data Email!',
        TextPart: 'Greetings from Mailjet!',
        HTMLPart:
            '<h3>Dear passenger data request</h3><br><hr>' + Object.keys(frm).map((k)=> `<b>${k}</b>: ${frm[k]}`).join('<br/>'),
      },
    ],
  }).then(()=> res.render('sended.pug'))


});

app.listen(port, () => console.log(`Servidor iniciado en el puerto ${port}`));
