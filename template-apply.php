<?php
/**
*  Template Name:申请试用
*
*/
?>
<?php get_header(); ?>
<?php get_header('masthead'); ?>
<style type="text/css">
.huoxing-body
{
  margin-top: 10px;
}
</style>
<section>
  <div class="container">
    <div class="row main" id="top-main">
         <div class="center-content">
        <div class="block">
          
          <div class="welcome">
            欢迎成为我们的体验用户，不再被短暂的掩饰蒙蔽双眼。
          </div>
          <form class="register-form" method="post" action="/signup">
            <input type="hidden" name="acc_type" value="1">
            <div class="line name-line">
              <span class="name">您的称呼:<i class="need">*</i></span>
              <input class="small input-mHeight js-cached" name="fullname" type="text" placeholder="姓名">
              <select name="gender" class="js-cached">
                <option value="0">先生</option>
                <option value="1">女士</option>
              </select>
              <i class="status"></i>
              <span class="error error-line hidden">请填写真实姓名。</span>
            </div>

            <div class="line phone-line">
              <span class="name">联系电话:<i class="need">*</i></span>
              <input class="small input-mHeight js-cached" name="phone_number" type="text" placeholder="手机号码">
              <i class="status"></i>
              <span class="error error-line hidden">请填写您的手机号码。</span>
            </div>

            <div class="line IM-line">
              <span class="name">即时通讯:<i class="need">*</i></span>
              <input class="small input-mHeight js-cached" name="im_number" type="text" placeholder="实时通信联系方式">
              <select id="im-cate" name="im_category" class="js-cached">

                  <option value="1">QQ</option>
                
                  <option value="2">MSN</option>
                
                  <option value="3">GTalk</option>
                
                  <option value="4">Skype</option>
                
                
                  <option value="99">其他</option>
                
              </select>
              <i class="status"></i>
              <span class="error error-line hidden">请填写您的联系方式。</span>
            </div>

            <div class="line cate-line">
              <span class="name">企业类型:<i class="need">*</i></span>
              <select id="com-cate" class="company-cate js-cached" name="CompanyCate">
                <option value="-1">--所属行业--</option>
                
                  <option value="0">个人用户</option>
                
                  <option value="1">IT/互联网</option>
                
                  <option value="2">媒体</option>
                
                  <option value="3">金融</option>
                
                  <option value="4">科研</option>
                
                  <option value="5">教育</option>
                
                  <option value="6">文化娱乐</option>
                
                  <option value="7">商务服务</option>
                
                  <option value="8">政民服务</option>
                
                  <option value="9">其他</option>
                
              </select>
              <select id="com-size" class="company-size js-cached" name="CompanySize">
                <option value="-1">--企业规模--</option>
                
                  
                
                  
                
                  <option value="2">10-50人</option>
                
                  <option value="3">50-100人</option>
                
                  <option value="4">100-200人</option>
                
                  <option value="5">200-500人</option>
                
                  <option value="6">500-1000人</option>
                
                  <option value="7">1000人以上</option>
                
              </select>
              <i class="status"></i><br>
              <span class="error error-line hidden">请选择所属行业及企业规模。</span>
            </div>

            <div class="line com-line">
              <span class="name">企业名称:<i class="need">*</i></span>
              <input class="small input-mHeight js-cached" name="company_name" type="text" placeholder="个人用户请填写姓名">
              <i class="status"></i>
              <span class="error error-line hidden">请填写公司的全名，个人用户请填写姓名。</span>
            </div>

            <div class="line site-line">
              <span class="name">企业网站:<i class="need">*</i></span>
              <input class="small input-mHeight js-cached" name="site" type="text" placeholder="个人可输入个人主页或微博地址">
              <i class="status"></i>
              <span class="error error-line hidden">请填写网站url。</span>
            </div>


            <div class="align-center line register-line">
              <button class="btn register" type="submit">申请</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php get_footer('colophon'); ?>
<?php get_footer(); ?>