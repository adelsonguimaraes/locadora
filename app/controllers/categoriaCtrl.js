let categorias = [];

function setTbody (data) {
    let tbody = document.querySelector('tbody');
    let string = '';

    if (data==='') return false;

    data.forEach((e)=>{
        string += `<tr>`;
        string +=   `<td>${e.id}</td>`;
        string +=   `<td>${e.descricao}</td>`;
        string +=   `<td>${convert_data_hora(e.datacadastro)}</td>`;
        string +=   `<td>${convert_data_hora(e.dataedicao)}</td>`;
        string +=   `<td>
                        <div>
                            <button type="button" class="btn" onclick='editar(${e.id})'>
                                <i class="glyphicon glyphicon-pencil"></i>
                            </button>
                            <button type="button" class="btn" onclick='remover(${e.id})'>
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </div>
                    </td>`;
        string += `</tr>`;
    });
    tbody.innerHTML = string;
}

function listar () {

    fetch('./../../api/src/rest/categoria.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json'
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
            categorias = data.data;
            setTbody (data.data);
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
    categorias.forEach((e)=>{
        if (parseInt(e.id) === parseInt(id)) {
            result = e;
        }
    });

    let form = document.querySelector('#formCategoria');
    form.querySelector('input[name=id]').value = result.id;
    form.querySelector('input[name=descricao]').value = result.descricao;

    novo();
}

function remover (id) {
    let c = window.confirm('Você realmente deseja remover?');
    if (c) {
        fetch('./../../api/src/rest/categoria.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-type': 'application/json'
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

function salvar () {
    let obj = {};
    let form = document.querySelector('#formCategoria');
    obj.id = form.querySelector("input[name=id]").value;
    obj.descricao = form.querySelector("input[name=descricao]").value;

    let metodo = (parseInt(obj.id)>0) ? 'atualizar' : 'cadastrar';

    fetch('./../../api/src/rest/categoria.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json'
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

function cancelar () {
    document.querySelector('section#form').style.display = 'none';
    document.querySelector('section#grid').style.display = 'block';
    let form = document.querySelector('#formCategoria');
    form.querySelector("input[name=id]").value = "";
    form.reset();
}

function convert_data_hora (timestamp) {
    if (timestamp==='') return timestamp;

    let data_hora = timestamp.split(' ');
    let data = data_hora[0];
    let hora = data_hora[1];
    let split = data.split('-');
    return split[2] + '/' + split[1] + '/' + split[0] + ' ' + hora;
}