let consoles = [];

document.addEventListener("DOMContentLoaded", function() {
    
});

function setTbody(data) {
    let tbody = document.querySelector('tbody');
    let string = '';
    data.forEach((e)=>{
        string += `<tr>`;
        string += `<td>${e.id}</td>`;
        string += `<td>${e.nome}</td>`;
        string += `<td>${e.valor_hora}</td>`;
        string += `<td>
            <div class="btn-group">
                <button type="button" class="btn" onclick='editar(${e.id})'>
                    <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button type="button" class="btn" onclick="remover(${e.id})">
                    <i class="glyphicon glyphicon-trash"></i>
                </button>
            </div>
        </td>`;
        string += '</tr>';
    });
    tbody.innerHTML = string;
}

function listar () {

    fetch('./../../api/src/rest/console.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        mode: 'cors',
        cache: 'default',
        body: JSON.stringify({
            metodo: 'listar'
        })
    })
    .then((response)=>{
        if (response.ok) return response.json();
    })
    .then((data)=>{
        if (data.success) {
            consoles = data.data;
            setTbody(data.data);
        }else{
            alert(data.msg);
        }
    });
}
listar();

function novo () {
    document.querySelector('section#grid').style.display = 'none';
    document.querySelector('section#form').style.display = 'block';
}

function editar (id) {
    let result = null;
    consoles.forEach((e)=>{
        if (parseInt(e.id) === parseInt(id)) {
            result = e;
        }
    });

    let form = document.querySelector('#formConsole');
    form.querySelector('input[name=id]').value = result.id;
    form.querySelector('input[name=nome]').value = result.nome;
    form.querySelector('input[name=valor_hora]').value = result.valor_hora;

    novo();
}

function remover (id) {
    let c = window.confirm('Você realmente quer remover este console?');
    if (c) {
        fetch('./../../api/src/rest/console.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            mode: 'cors',
            cache: 'default',
            body: JSON.stringify({
                metodo: 'remover',
                data: id
            })
        })
        .then((response)=>{
            if (response.ok) return response.json();
        })
        .then((data)=>{
            if (data.success) {
                listar();
            }else{
                alert(data.msg);
            }
        });
    }
}

function cancelar () {
    document.querySelector('section#grid').style.display = 'block';
    document.querySelector('section#form').style.display = 'none';
    let form = document.querySelector('#formConsole');
    form.querySelector("input[name=id]").value = "";
    form.reset();
}

function salvar () {
    let obj = {};
    let form = document.querySelector('#formConsole');
    obj.id = form.querySelector('input[name=id]').value;
    obj.nome = form.querySelector('input[name=nome]').value;
    obj.valor_hora = form.querySelector('input[name=valor_hora]').value;

    let metodo = (parseInt(obj.id)>0) ? 'atualizar' : 'cadastrar';

    fetch('./../../api/src/rest/console.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        mode: 'cors',
        cache: 'default',
        body: JSON.stringify({
            metodo: metodo,
            data: obj
        })
    })
    .then((response)=>{
        if (response.ok) return response.json();
    })
    .then((data)=>{
        if (data.success) {
            cancelar();
            listar();
        }else{
            alert(data.msg);
        }
    });

}