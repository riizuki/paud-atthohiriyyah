const http = require('http');

const payload = JSON.stringify({
  title: 'Test Agenda',
  description: 'This is a test agenda',
  startDate: '2024-01-01',
  endDate: '2024-01-02',
  location: 'Test Location'
});

const options = {
  hostname: '127.0.0.1',
  port: 3000,
  path: '/admin/agenda/create',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': Buffer.byteLength(payload)
  }
};

const req = http.request(options, (res) => {
  console.log(`STATUS: ${res.statusCode}`);
  console.log(`HEADERS:`, res.headers);
  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });
  res.on('end', () => {
    console.log('RESPONSE BODY:', data);
  });
});

req.on('error', (e) => {
  console.error(`problem with request: ${e.message}`);
});

console.log('Sending POST request to /admin/agenda/create');
console.log('Payload:', payload);
req.write(payload);
req.end();
