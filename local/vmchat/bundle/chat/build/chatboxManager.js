define(["jquery"],function(a){Array.indexOf||(Array.prototype.indexOf=function(a){for(var b=0;b<this.length;b++)if(this[b]==a)return b;return-1});var b=function(){var b=new Array,c=new Array,d={width:230,gap:20,maxBoxes:5,messageSent:function(b,c,d){a("#"+b).chatbox("option","boxManager").addMsg(c,d)}},e=function(b){a.extend(d,b)},f=function(d){var e=d;c=a.grep(c,function(a){return e!=a}),b=a.grep(b,function(a){return e!=a})},g=function(){return 285+(d.width+d.gap)*c.length},h=function(e){var f=c.indexOf(e);if(f!=-1){c.splice(f,1),b.splice(f,1),diff=d.width+d.gap;for(var g=f;g<c.length;g++)offset=a("#"+c[g]).chatbox("option","offset"),a("#"+c[g]).chatbox("option","offset",offset-diff)}else alert("should not happen: "+e)},i=function(e,f,i){var k=c.indexOf(e),l=b.indexOf(e);if(k!=-1);else if(l!=-1){a("#"+e).chatbox("option","offset",g());var m=a("#"+e).chatbox("option","boxManager");m.toggleBox(),c.push(e);var n=-1;n=a("#tabs li").index(a("#tabcb"+e)),a(a(".tabs li")[n]).css("display","list-item"),a("#tabs").tabs("option","active",n),a("#tabs").tabs("show")}else{void 0==f.last_name&&(f.last_name="");var o=document.createElement("div");o.setAttribute("id",e),a(o).chatbox({id:e,user:f,title:f.first_name+" "+f.last_name,hidden:!1,width:d.width,offset:g(),messageSent:j,boxClosed:h}),b.push(e),c.push(e)}},j=function(a,c,e){b.indexOf(c.userid);d.messageSent(a,c,e)},k=function(b,c){a("#"+b.userid).chatbox("option","boxManager").addMsg(b.name,c)};return{init:e,addBox:i,delBox:f,dispatch:k}}();return b});