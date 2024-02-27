// Получаем все ячейки первого столбца
const rows = document.querySelectorAll("tr td:nth-child(7)");
          
// Записываем в них текущее время
rows.forEach((row) => {
    row.innerHTML = new Date().toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + new Date().toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
});