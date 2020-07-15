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
        attaches: ({ data: e }) => {
                var icon='fa-file';
                var color='btn-default';
                switch(e.file.extension){
                    case 'pdf':
                        icon='fa-file-pdf-o';
                        color='bg-maroon';
                    break;
                    case'docx':
                        icon='fa-file-word-o';
                        color='bg-blue';
                    break;
                    case'doc':
                        icon='fa-file-word-o';
                        color='bg-blue';
                    break;
                    case'xls':
                        icon='fa-file-excel-o';
                        color='bg-green';
                    break;
                    case'xlsx':
                        icon='fa-file-excel-o';
                        color='bg-green';
                    break;
                    default:
                        icon='fa-file';
                        color='bg-yellow';
                    break;
                }

            return `<div class="info-box ${color}">`+
                `<span class="info-box-icon">`+
                `<i class=" `+icon+`" style="border:1px solid; padding:10px; border-top-left-radius:5px; border-bottom-right-radius:5px; font-size:8px;">.${e.file.extension}</i>`+
                `</span>`+
                `<div class="info-box-content">`+
                  `<span class="info-box-text">${e.file.name}</span>`+
                  // `<span class="info-box-number">92,050</span>`+
                  `<div class="progress">`+
                  `  <div class="progress-bar" style="width: 20%"></div>`+
                 ` </div>`+
                `  <span class="progress-description"><a href="${e.file.url}" target="_blank" class="text-white"><i class="fa fa-download"></i> Download / View</a></span>`+
               ` </div>`+
              `</div>`;

            return `<div class="" style="margin-bottom:5px; margin-top:5px;"><a href="${e.file.url}" target="_blank" class="btn  `+color+`"  ><i class=" `+icon+`" style="border:1px solid; padding:5px; border-top-left-radius:5px; border-bottom-right-radius:5px; font-size:8px;">.${e.file.extension}</i> &nbsp;&nbsp;&nbsp;  ${e.file.name}</a></div>`;
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
