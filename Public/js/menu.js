
// 左侧菜单鼠标效果
var menu = {
	"name":{"MESSAGE":"message","CONTACTS":"contacts","COMMUNITY":"community","WEIBO":"weibo","CHATROOM":"chatroom"},
	"img_src":[
	"Public/image/menu_message.png",
	"Public/image/menu_contacts.png",
	"Public/image/menu_community.png",
	"Public/image/menu_weibo.png",
	"Public/image/menu_chatroom.png"
	],
	"img_hover_src":[
	"Public/image/menu_message_hover.png",
	"Public/image/menu_contacts_hover.png",
	"Public/image/menu_community_hover.png",
	"Public/image/menu_weibo_hover.png",
	"Public/image/menu_chatroom_hover.png"
	],	
	"mouseOverMessage":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[0];},
	"mouseOverContacts":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[1];},	
	"mouseOverCommunity":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[2];},	
	"mouseOverWeibo":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[3];},	
	"mouseOverChatroom":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[4];},
	"mouseOutMessage":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[0];},
	"mouseOutContacts":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[1];},	
	"mouseOutCommunity":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[2];},	
	"mouseOutWeibo":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[3];},	
	"mouseOutChatroom":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[4];},
	"removeEvent":function(id,menuName){
		if(id==null){
			alert("id不能为空");
			return;	
		}
		var _img = document.getElementById(id).getElementsByTagName("a").item(0).getElementsByTagName("img").item(0);
		switch(menuName){
			case this.name.MESSAGE: 
			_img.src = this.img_hover_src[0];break;
			case this.name.CONTACTS:
			_img.src = this.img_hover_src[1];break;
			case this.name.COMMUNITY:
			_img.src = this.img_hover_src[2];break;
			case this.name.WEIBO:
			_img.src = this.img_hover_src[3];break;
			case this.name.CHATROOM:
			_img.src = this.img_hover_src[4];break;
				
			default:alert("菜单名称无效");break;
		}
		document.getElementById(id).removeAttribute("onmouseover");	
		document.getElementById(id).removeAttribute("onmouseout");
	},
	"bindEvent":function(){
		// 鼠标移入
		document.getElementById("menu_message").setAttribute("onmouseover","menu.mouseOverMessage(this)");	
		document.getElementById("menu_contacts").setAttribute("onmouseover","menu.mouseOverContacts(this)");
		document.getElementById("menu_community").setAttribute("onmouseover","menu.mouseOverCommunity(this)");
		document.getElementById("menu_weibo").setAttribute("onmouseover","menu.mouseOverWeibo(this)");
		document.getElementById("menu_chatroom").setAttribute("onmouseover","menu.mouseOverChatroom(this)");
		// 鼠标移出
		document.getElementById("menu_message").setAttribute("onmouseout","menu.mouseOutMessage(this)");	
		document.getElementById("menu_contacts").setAttribute("onmouseout","menu.mouseOutContacts(this)");
		document.getElementById("menu_community").setAttribute("onmouseout","menu.mouseOutCommunity(this)");
		document.getElementById("menu_weibo").setAttribute("onmouseout","menu.mouseOutWeibo(this)");
		document.getElementById("menu_chatroom").setAttribute("onmouseout","menu.mouseOutChatroom(this)");
	}
	
}

// 右侧--消息页--菜单鼠标效果
var menu_message = {
	"name":{"CONTACTS":"contacts","COMMUNITY":"conmmunity","EXERCISE":"exercise","LETTER":"letter","NOTICE":"notice"},
	"img_src":[
	"Public/image/menu_message_contacts.png",
	"Public/image/menu_message_community.png",
	"Public/image/menu_message_exercise.png",
	"Public/image/menu_message_letter.png",
	"Public/image/menu_message_notice.png"
	],
	"img_hover_src":[
	"Public/image/menu_message_contacts_hover.png",
	"Public/image/menu_message_community_hover.png",
	"Public/image/menu_message_exercise_hover.png",
	"Public/image/menu_message_letter_hover.png",
	"Public/image/menu_message_notice_hover.png"
	],	
	"mouseOverContacts":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[0];},
	"mouseOverCommunity":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[1];},	
	"mouseOverExercise":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[2];},	
	"mouseOverLetter":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[3];},	
	"mouseOverNotice":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[4];},
	"mouseOutContacts":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[0];},
	"mouseOutCommunity":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[1];},	
	"mouseOutExercise":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[2];},	
	"mouseOutLetter":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[3];},	
	"mouseOutNotice":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[4];},
	"removeEvent":function(id,menuName){
		if(id==null){
			alert("id不能为空");
			return;	
		}
		var _img = document.getElementById(id).getElementsByTagName("a").item(0).getElementsByTagName("img").item(0);
		switch(menuName){
			case this.name.CONTACTS: 
			_img.src = this.img_hover_src[0];break;
			case this.name.COMMUNITY:
			_img.src = this.img_hover_src[1];break;
			case this.name.EXERCISE:
			_img.src = this.img_hover_src[2];break;
			case this.name.LETTER:
			_img.src = this.img_hover_src[3];break;
			case this.name.NOTICE:
			_img.src = this.img_hover_src[4];break;
				
			default:alert("菜单名称无效");break;
		}
		document.getElementById(id).removeAttribute("onmouseover");	
		document.getElementById(id).removeAttribute("onmouseout");
	},
	"bindEvent":function(){

		// 鼠标移入
		document.getElementById("menu_message_contacts").setAttribute("onmouseover","menu_message.mouseOverContacts(this)");	
		document.getElementById("menu_message_community").setAttribute("onmouseover","menu_message.mouseOverCommunity(this)");
		document.getElementById("menu_message_exercise").setAttribute("onmouseover","menu_message.mouseOverExercise(this)");
		document.getElementById("menu_message_letter").setAttribute("onmouseover","menu_message.mouseOverLetter(this)");
		document.getElementById("menu_message_notice").setAttribute("onmouseover","menu_message.mouseOverNotice(this)");
		// 鼠标移出
		document.getElementById("menu_message_contacts").setAttribute("onmouseout","menu_message.mouseOutContacts(this)");	
		document.getElementById("menu_message_community").setAttribute("onmouseout","menu_message.mouseOutCommunity(this)");
		document.getElementById("menu_message_exercise").setAttribute("onmouseout","menu_message.mouseOutExercise(this)");
		document.getElementById("menu_message_letter").setAttribute("onmouseout","menu_message.mouseOutLetter(this)");
		document.getElementById("menu_message_notice").setAttribute("onmouseout","menu_message.mouseOutNotice(this)");
	}
}



// 右侧--人脉页--菜单鼠标效果
var menu_contacts = {
	"name":{"MY":"my","SQUARE":"square","SEARCH":"search","ATTENTION":"attention","ADDFRIEND":"addfriend","INVITE":"invite"},
	"img_src":[
	"Public/image/menu_contacts_my.png",
	"Public/image/menu_contacts_square.png",
	"Public/image/menu_contacts_search.png",
	"Public/image/menu_contacts_attention.png",
	"Public/image/menu_contacts_addfriend.png",
	"Public/image/menu_contacts_invite.png"
	],
	"img_hover_src":[
	"Public/image/menu_contacts_my_hover.png",
	"Public/image/menu_contacts_square_hover.png",
	"Public/image/menu_contacts_search_hover.png",
	"Public/image/menu_contacts_attention_hover.png",
	"Public/image/menu_contacts_addfriend_hover.png",
	"Public/image/menu_contacts_invite_hover.png"
	],	
	"mouseOverMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[0];},
	"mouseOverSquare":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[1];},	
	"mouseOverSearch":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[2];},	
	"mouseOverAttention":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[3];},
	"mouseOverAddfriend":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[4];},	
	"mouseOverInvite":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[5];},
	"mouseOutMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[0];},
	"mouseOutSquare":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[1];},	
	"mouseOutSearch":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[2];},	
	"mouseOutAttention":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[3];},
	"mouseOutAddfriend":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[4];},	
	"mouseOutInvite":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[5];},
	"removeEvent":function(id,menuName){
		if(id==null){
			alert("id不能为空");
			return;	
		}
		var _img = document.getElementById(id).getElementsByTagName("a").item(0).getElementsByTagName("img").item(0);
		switch(menuName){
			case this.name.MY: 
			_img.src = this.img_hover_src[0];break;
			case this.name.SQUARE:
			_img.src = this.img_hover_src[1];break;
			case this.name.SEARCH:
			_img.src = this.img_hover_src[2];break;
			case this.name.ATTENTION:
			_img.src = this.img_hover_src[3];break;
			case this.name.ADDFRIEND:
			_img.src = this.img_hover_src[4];break;
			case this.name.INVITE:
			_img.src = this.img_hover_src[5];break;
				
			default:alert("菜单名称无效");break;
		}
		document.getElementById(id).removeAttribute("onmouseover");	
		document.getElementById(id).removeAttribute("onmouseout");
	},
	"bindEvent":function(){
		// 鼠标移入
		document.getElementById("menu_contacts_my").setAttribute("onmouseover","menu_contacts.mouseOverMy(this)");	
		document.getElementById("menu_contacts_square").setAttribute("onmouseover","menu_contacts.mouseOverSquare(this)");
		document.getElementById("menu_contacts_search").setAttribute("onmouseover","menu_contacts.mouseOverSearch(this)");
		document.getElementById("menu_contacts_attention").setAttribute("onmouseover","menu_contacts.mouseOverAttention(this)");
		document.getElementById("menu_contacts_addfriend").setAttribute("onmouseover","menu_contacts.mouseOverAddfriend(this)");
		document.getElementById("menu_contacts_invite").setAttribute("onmouseover","menu_contacts.mouseOverInvite(this)");
		// 鼠标移出
		document.getElementById("menu_contacts_my").setAttribute("onmouseout","menu_contacts.mouseOutMy(this)");	
		document.getElementById("menu_contacts_square").setAttribute("onmouseout","menu_contacts.mouseOutSquare(this)");
		document.getElementById("menu_contacts_search").setAttribute("onmouseout","menu_contacts.mouseOutSearch(this)");
		document.getElementById("menu_contacts_attention").setAttribute("onmouseout","menu_contacts.mouseOutAttention(this)");
		document.getElementById("menu_contacts_addfriend").setAttribute("onmouseout","menu_contacts.mouseOutAddfriend(this)");
		document.getElementById("menu_contacts_invite").setAttribute("onmouseout","menu_contacts.mouseOutInvite(this)");
	}
}


// 右侧--圈子页--菜单鼠标效果
var menu_community = {
	"name":{"MY":"my","ALL":"all","CREATE":"create","DISCUSS":"discuss","CHAT":"chat","TOPIC":"topic"},
	"img_src":[
	"Public/image/menu_community_my.png",
	"Public/image/menu_community_all.png",
	"Public/image/menu_community_create.png",
	"Public/image/menu_community_discuss.png",
	"Public/image/menu_community_chat.png",
	"Public/image/menu_community_topic.png"
	],
	"img_hover_src":[
	"Public/image/menu_community_my_hover.png",
	"Public/image/menu_community_all_hover.png",
	"Public/image/menu_community_create_hover.png",
	"Public/image/menu_community_discuss_hover.png",
	"Public/image/menu_community_chat_hover.png",
	"Public/image/menu_community_topic_hover.png"
	],	
	"mouseOverMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[0];},
	"mouseOverAll":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[1];},	
	"mouseOverCreate":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[2];},	
	"mouseOverDiscuss":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[3];},
	"mouseOverChat":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[4];},	
	"mouseOverTopic":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[5];},
	"mouseOutMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[0];},
	"mouseOutAll":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[1];},	
	"mouseOutCreate":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[2];},	
	"mouseOutDiscuss":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[3];},
	"mouseOutChat":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[4];},	
	"mouseOutTopic":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[5];},
	"removeEvent":function(id,menuName){
		if(id==null){
			alert("id不能为空");
			return;	
		}
		var _img = document.getElementById(id).getElementsByTagName("a").item(0).getElementsByTagName("img").item(0);
		switch(menuName){
			case this.name.MY: 
			_img.src = this.img_hover_src[0];break;
			case this.name.ALL:
			_img.src = this.img_hover_src[1];break;
			case this.name.CREATE:
			_img.src = this.img_hover_src[2];break;
			case this.name.DISCUSS:
			_img.src = this.img_hover_src[3];break;
			case this.name.CHAT:
			_img.src = this.img_hover_src[4];break;
			case this.name.TOPIC:
			_img.src = this.img_hover_src[5];break;
				
			default:alert("菜单名称无效");break;
		}
		document.getElementById(id).removeAttribute("onmouseover");	
		document.getElementById(id).removeAttribute("onmouseout");
	},
	"bindEvent":function(){
		// 鼠标移入
		document.getElementById("menu_community_my").setAttribute("onmouseover","menu_community.mouseOverMy(this)");	
		document.getElementById("menu_community_all").setAttribute("onmouseover","menu_community.mouseOverAll(this)");
		document.getElementById("menu_community_create").setAttribute("onmouseover","menu_community.mouseOverCreate(this)");
		document.getElementById("menu_community_discuss").setAttribute("onmouseover","menu_community.mouseOverDiscuss(this)");
		document.getElementById("menu_community_chat").setAttribute("onmouseover","menu_community.mouseOverChat(this)");
		document.getElementById("menu_community_topic").setAttribute("onmouseover","menu_community.mouseOverTopic(this)");
		// 鼠标移出
		document.getElementById("menu_community_my").setAttribute("onmouseout","menu_community.mouseOutMy(this)");	
		document.getElementById("menu_community_all").setAttribute("onmouseout","menu_community.mouseOutAll(this)");
		document.getElementById("menu_community_create").setAttribute("onmouseout","menu_community.mouseOutCreate(this)");
		document.getElementById("menu_community_discuss").setAttribute("onmouseout","menu_community.mouseOutDiscuss(this)");
		document.getElementById("menu_community_chat").setAttribute("onmouseout","menu_community.mouseOutChat(this)");
		document.getElementById("menu_community_topic").setAttribute("onmouseout","menu_community.mouseOutTopic(this)");
	}
}



// 右侧--微博页--菜单鼠标效果
var menu_weibo = {
	"name":{"NEW":"new","MY":"my","COMMENT":"comment","COLLECT":"collect"},
	"img_src":[
	"Public/image/menu_weibo_new.png",
	"Public/image/menu_weibo_my.png",
	"Public/image/menu_weibo_comment.png",
	"Public/image/menu_weibo_collect.png"
	],
	"img_hover_src":[
	"Public/image/menu_weibo_new_hover.png",
	"Public/image/menu_weibo_my_hover.png",
	"Public/image/menu_weibo_comment_hover.png",
	"Public/image/menu_weibo_collect_hover.png"
	],	
	"mouseOverNew":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[0];},
	"mouseOverMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[1];},	
	"mouseOverComment":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[2];},	
	"mouseOverCollect":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_hover_src[3];},
	
	"mouseOutNew":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[0];},
	"mouseOutMy":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[1];},	
	"mouseOutComment":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[2];},	
	"mouseOutCollect":function(curr){curr.getElementsByTagName("a").item(0).getElementsByTagName("img").item(0).src = this.img_src[3];},
	
	"removeEvent":function(id,menuName){
		if(id==null){
			alert("id不能为空");
			return;	
		}
		var _img = document.getElementById(id).getElementsByTagName("a").item(0).getElementsByTagName("img").item(0);
		switch(menuName){
			case this.name.NEW: 
			_img.src = this.img_hover_src[0];break;
			case this.name.MY:
			_img.src = this.img_hover_src[1];break;
			case this.name.COMMENT:
			_img.src = this.img_hover_src[2];break;
			case this.name.COLLECT:
			_img.src = this.img_hover_src[3];break;
				
			default:alert("菜单名称无效");break;
		}
		document.getElementById(id).removeAttribute("onmouseover");	
		document.getElementById(id).removeAttribute("onmouseout");
	},
	"bindEvent":function(){
		// 鼠标移入
		document.getElementById("menu_weibo_new").setAttribute("onmouseover","menu_weibo.mouseOverNew(this)");	
		document.getElementById("menu_weibo_my").setAttribute("onmouseover","menu_weibo.mouseOverMy(this)");
		document.getElementById("menu_weibo_comment").setAttribute("onmouseover","menu_weibo.mouseOverComment(this)");
		document.getElementById("menu_weibo_collect").setAttribute("onmouseover","menu_weibo.mouseOverCollect(this)");
		// 鼠标移出
		document.getElementById("menu_weibo_new").setAttribute("onmouseout","menu_weibo.mouseOutNew(this)");	
		document.getElementById("menu_weibo_my").setAttribute("onmouseout","menu_weibo.mouseOutMy(this)");
		document.getElementById("menu_weibo_comment").setAttribute("onmouseout","menu_weibo.mouseOutComment(this)");
		document.getElementById("menu_weibo_collect").setAttribute("onmouseout","menu_weibo.mouseOutCollect(this)");
	}
}


