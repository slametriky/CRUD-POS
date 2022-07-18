<div class="main-sidebar">
<aside id="sidebar-wrapper">
	<div class="sidebar-brand">
		<a href="{{ url('/') }}">POS</a>
	</div>
	<div class="sidebar-brand sidebar-brand-sm">
		<a href="{{ url('/') }}">POS</a>
	</div>
	<ul class="sidebar-menu">          
		<li class="menu-header">Master</li>
		<li class="nav-item dropdown {{ set_active(['customers.index', 'products.index', 'sales-orders.index']) }}">
			<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Data Master</span></a>
			<ul class="dropdown-menu">
				<li class="{{ set_active('customers.index') }}"><a class="nav-link" href="{{ route('customers.index') }}">Pelanggan</a></li>              
				<li class="{{ set_active('products.index') }}"><a class="nav-link" href="{{ route('products.index') }}">Produk</a></li>   								
				<li class="{{ set_active('sales-orders.index') }}"><a class="nav-link" href="{{ route('sales-orders.index') }}">Transaksi Penjualan</a></li>   								
			</ul>
		</li>          		        
	</ul>	
</aside>
</div>