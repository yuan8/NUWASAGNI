var edjsHTML = (function () {
    "use strict";
    var e = {
        zdelimiter: () => "<br/>",
        header: ({ data: e }) => `<h${e.level}> ${e.text} </h${e.level}>`,
        paragraph: ({ data: e }) => `<p> ${e.text} </p>`,
        list: ({ data: e }) => {
            let t = "unordered" === e.style ? "ul" : "ol";
            return `<${t}> ${e.items.map((e) => `<li> ${e} </li>`).reduce((e, t) => e + t, "")} </${t}>`;
        },
        checklist:({data:e})=>{
        	let t = "ul"; 
            return `<${t} class="list-group"> ${e.items.map((e) => {
            	return `<li class="list-group-item"><i class="fa fa-check" style="color:`+(e.checked?'blue':'#ddd')+`"></i> ${e.text} </li>`;

            }).reduce((e, t) => e + t, "")} </${t}>`;
        },
        quote:({data:e})=>{
        	return '<div class="bg-warning" style="padding:10px; text-align:'+(e.alignment!=''?e.alignment:'left')+'; border-radius:10px; "><i>"'+e.text+'"</i><br><p>@<b>'+e.caption+'</b></p></div>';
        },
        linkTool: ({ data: e }) => {
          return '<a href="'+e.link+'" class="btn btn-primary btn-xs"><i class="fa fa-link"></i> '+e.link+'</a>';
        },

        table: ({ data: e }) => {
            let t = "table";
            let tb = "body";
            return `<${t} class="table table-bordered"><${tb}> ${e.content.map((e) => {
            	var dm='<tr>';
            	e.forEach((i)=>{
            		dm+='<td>'+i+'</td>'
            	});
            	dm+='</tr>';

            	return dm;

            }).reduce((e, t) => e + t, "")} </${tb}></${t}>`;
        },
        image: ({ data: e }) => {
            let t = e.caption ? e.caption : "Image";
            return `<div class="text-center col-md-12"><img src="${e.file.url}" class="img-responsive" style="max-width:80%; margin:auto;" alt="${t}" /></div>`;
        },
        paragraph: ({ data: e }) => `<p> ${e.text} </p>`,
        embed: ({ data: e }) => {

        	switch(e.service){
        		case 'youtube':
        			return '<p><i>'+e.caption+'</i></p><iframe class="text-center" width="100%" height="'+e.height+'" src="'+e.embed+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        		break;



        	}

        	return '<a href="'+e.source+'" class="btn-xs btn-warning">'+e.source+'</a>';

        },
    };
    function t(e) {
        return new Error(`[31m The Parser function of type "${e}" is not defined. \n\n  Define your custom parser functions as: [34mhttps://github.com/pavittarx/editorjs-html#extend-for-custom-blocks [0m`);
    }
    return (r = {}) => (Object.assign(e, r), { parse: ({ blocks: r }) => r.map((r) => (e[r.type] ? e[r.type](r) : t(r.type))), parseBlock: (r) => (e[r.type] ? e[r.type](r) : t(r.type)) });
})();
