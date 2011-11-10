	<a class="home-menu" id="home" rel="1"  href="index.php">
	 </a>
<ul class="main-menu">
	{foreach from=$blockCategTree.children item=child name=blockCategTree}
			{if $smarty.foreach.blockCategTree.last}
				{include file="$tpl_dir./shop$shop/menu-dropdown.tpl" node=$child last='true'}
			{else}
				{include file="$tpl_dir./shop$shop/menu-dropdown.tpl" node=$child last='false'}
			{/if}
		{/foreach}
{*
    <li >
        <h3><a href="2-thoi-trang-nu" >thời trang nữ</a></h3>
        <div id="m1"  class="menu-extend women">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3" class="bottom">
                        <h4><span class="menu-ext-women">thời trang nữ</span></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li><h5><a href="/11-ao-khoac">Áo khoác</a></h5></li>
                            <li><h5><a href="/12-ao-kieu">Áo kiểu</a></h5></li>
                            <li><h5><a href="/13-ao-thun">Áo thun</a></h5></li>
                            <li><h5><a href="/14-dao-pho">Dạo phố</a></h5></li>
                            <li><h5><a href="/15-jumpsuit">Jumpsuit</a></h5></li>
                            <li><h5><a href="/16-quan-jean">Quần Jean</a></h5></li>
                            <li><h5><a href="/17-quan-kaki">Quần kaki</a></h5></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><h5><a href="/18-quan-legging">Quần legging</a></h5></li>
                            <li><h5><a href="/19-quan-lung-sooc">Quần lửng/ Sooc</a></h5></li>
                            <li><h5><a href="/20-so-mi">Sơ mi</a></h5></li>
                            <li><h5><a href="/21-vay">Váy</a></h5></li>
                            <li><h5><a href="/22-dam-cong-so">Đầm công sở</a></h5></li>
                            <li><h5><a href="/23-dam-du-tiec">Đầm dự tiệc</a></h5></li>
                        </ul>
                    </td>
                    <td class="left">
                        <ul>
                            <li><h5><a href="#">Mới về</a></h5></li>
                            <li><h5><a href="#">Xu hướng</a></h5></li>
                            <li><h5><a href="#">Yêu thích</a></h5></li>
                            <li><h5><a href="#">Giảm giá</a></h5></li>
                        </ul>
                    </td>	
                </tr>
            </table>
        </div>
    </li>
    <li>
        <h3><a href="#" >for teen</a></h3>
        <div id="m2"  class="menu-extend men">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3" class="bottom">
                        <h4><span class="menu-ext-men">for teen</span></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li><h5><a href="#">Áo</a></h5></li>
                            <li><h5><a href="#">Áo khoác</a></h5></li>
                            <li><h5><a href="#">Áo thun</a></h5></li>
                            <li><h5><a href="#">Jumpsuit</a></h5></li>
                            
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><h5><a href="#">Quần Jean</a></h5></li>
                            <li><h5><a href="#">Quần Sooc</a></h5></li>
                            <li><h5><a href="#">Váy ngắn</a></h5></li>
                            <li><h5><a href="#">Đầm</a></h5></li>
                        </ul>
                    </td>
                    <td class="left">
                         <ul>
                            <li><h5><a href="#">Mới về</a></h5></li>
                            <li><h5><a href="#">Xu hướng</a></h5></li>
                            <li><h5><a href="#">Yêu thích</a></h5></li>
                            <li><h5><a href="#">Giảm giá</a></h5></li>
                        </ul>
                    </td>	
                </tr>
            </table>
        </div>
    </li>
    <li>
        <h3><a href="#" >mẹ & bé</a></h3>
        <div id="m3"  class="menu-extend children">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3" class="bottom">
                        <h4><span class="menu-ext-men">mẹ & bé</span></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li><h5><a href="#">Áo bé gái</a></h5></li>
                            <li><h5><a href="#">Áo bé trai</a></h5></li>
                            <li><h5><a href="#">Bộ đồ bé trai</a></h5></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><h5><a href="#">Quần bé gái</a></h5></li>
                            <li><h5><a href="#">Quần bé trai</a></h5></li>
                            <li><h5><a href="#">Đầm bé gái</a></h5></li>
                        </ul>
                    </td>
                    <td class="left">
                         <ul>
                            <li><h5><a href="#">Mới về</a></h5></li>
                            <li><h5><a href="#">Xu hướng</a></h5></li>
                            <li><h5><a href="#">Yêu thích</a></h5></li>
                            <li><h5><a href="#">Giảm giá</a></h5></li>
                        </ul>
                    </td>	
                </tr>
            </table>
        </div>
    </li>
    <li>
        <h3><a href="#">thời trang nam</a></h3>
        <div id="m4"  class="menu-extend children">
        	<table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3" class="bottom">
                        <h4><span class="menu-ext-men">thời trang nam</span></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li><h5><a href="#">Quần lửng/sooc</a></h5></li>
                            <li><h5><a href="#">Quần Jean</a></h5></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><h5><a href="#">Áo thun</a></h5></li>
                            <li><h5><a href="#">Sơ mi</a></h5></li>

                        </ul>
                    </td>
                    <td class="left">
                         <ul>
                            <li><h5><a href="#">Mới về</a></h5></li>
                            <li><h5><a href="#">Xu hướng</a></h5></li>
                            <li><h5><a href="#">Yêu thích</a></h5></li>
                            <li><h5><a href="#">Giảm giá</a></h5></li>
                        </ul>
                    </td>	
                </tr>
            </table>
        </div>
    </li>
    <li>
        <h3><a href="#" >mắt kính</a></h3>
        <div id="m5"  class="menu-extend glasses">
        	<table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="3" class="bottom">
                        <h4><span class="menu-ext-men">Mắt kính</span></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li><h5><a href="#">Quần lửng/sooc</a></h5></li>
                            <li><h5><a href="#">Quần Jean</a></h5></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><h5><a href="#">Áo thun</a></h5></li>
                            <li><h5><a href="#">Sơ mi</a></h5></li>

                        </ul>
                    </td>
                    <td class="left">
                         <ul>
                            <li><h5><a href="#">Mới về</a></h5></li>
                            <li><h5><a href="#">Xu hướng</a></h5></li>
                            <li><h5><a href="#">Yêu thích</a></h5></li>
                            <li><h5><a href="#">Giảm giá</a></h5></li>
                        </ul>
                    </td>	
                </tr>
            </table>
        </div>
    </li>
*}
   
</ul>