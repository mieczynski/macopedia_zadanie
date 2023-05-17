function createTableFromObjects() {
    const table = document.createElement('table');
    const headerRow = document.createElement('tr');
    const data = document.querySelector('.js-products');
    var products = JSON.parse(data.dataset.products);

    // Create table header row
    const keys = Object.keys(products[0]);
    for (const key of keys) {
        const headerCell = document.createElement('th');
        headerCell.textContent = key;
        headerRow.appendChild(headerCell);
    }
    table.appendChild(headerRow);

    // Create table data rows
    for (const obj of products) {
        const dataRow = document.createElement('tr');
        for (const key of keys) {
            const dataCell = document.createElement('td');
            dataCell.textContent = obj[key];
            dataRow.appendChild(dataCell);
        }
        table.appendChild(dataRow);
    }
    return table

}

document.addEventListener('DOMContentLoaded', init, false);

let data, table, products, categories;
const pageSize = 10;
let curPage = 1;

async function init() {

    table = document.querySelector('#productsTable tbody');

    data = document.querySelector('.js-products');

    products = JSON.parse(data.dataset.products);
    categories = JSON.parse(data.dataset.categories);
    renderTable();


    document.querySelector('#nextButton').addEventListener('click', nextPage, false);
    document.querySelector('#prevButton').addEventListener('click', previousPage, false);
}

function renderTable() {
    let result = '';
    products.filter((row, index) => {
        let start = (curPage - 1) * pageSize;
        let end = curPage * pageSize;
        if (index >= start && index < end) return true;
    }).forEach(p => {
        result += `<tr>
     <td>${p.name}</td>
     <td>${p.index}</td>
      <td>${getCategoriesSelect(p.id, p.category)}</td>
     </tr>`;
    });

    table.innerHTML = result;
}

function updateCategory(productId) {
    const categorySelectId = 'categorySelect' + productId
    var e = document.getElementById(categorySelectId);
    const categoryId = e.value
    let postObj = {
        productId: productId,
        categoryId: categoryId
    }
    if (postJSON(postObj)) {
        e.value = categoryId;
        products = JSON.parse(data.dataset.products);
    }
}

async function postJSON(data) {
    try {
        const response = await fetch("/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.status == 201)
            return true;
    } catch (error) {
    }
}

function getCategoriesSelect(productId, currentCategory) {

    const categoryId = 'categorySelect' + productId
    var select = `<select onchange="updateCategory(${productId})" id="${categoryId}" >`
    select += `<option value="0" ></option>\n`

    if (currentCategory)
        select += `<option selected="selected" value=${currentCategory.id}>${currentCategory.name}</option>\n`
    else
        select += `<option selected="selected" ></option>\n`

    categories.forEach(c => {
        if (currentCategory) {
            if (c.name !== currentCategory.name)
                select += `<option value=${c.id}>${c.name}</option>\n`
        } else {
            select += `<option value=${c.id}>${c.name}</option>\n`
        }

    })
    select += '</select>'
    return select;
}

function previousPage() {
    if (curPage > 1) curPage--;
    renderTable();
}

function nextPage() {
    if ((curPage * pageSize) < products.length) curPage++;
    renderTable();
}