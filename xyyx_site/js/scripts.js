$('.carousel').carousel({
  interval: 2000 // in milliseconds
})

// �Զ����ʹ���
(function(){
    var bp = document.createElement('script');
    bp.src = '//push.zhanzhang.baidu.com/push.js';
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();