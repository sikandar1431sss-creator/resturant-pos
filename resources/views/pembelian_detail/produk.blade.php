<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #0f172a; color: #fff; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title" style="font-weight: 800; font-size: 16px; margin: 0; color: #fff;">
                    <i class="fa fa-cubes text-warning"></i> Select Item for Purchase
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; margin: 0; font-size: 24px;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" style="margin-bottom: 16px; border-bottom: 2px solid #e2e8f0;">
                    <li class="active">
                        <a href="#tab-raw-materials" data-toggle="tab" style="font-weight: 700; font-size: 13.5px; border-radius: 8px 8px 0 0;">
                            <i class="fa fa-cubes text-primary"></i> Raw Stock Materials ({{ isset($rawMaterials) ? $rawMaterials->count() : 0 }})
                        </a>
                    </li>
                    <li>
                        <a href="#tab-menu-products" data-toggle="tab" style="font-weight: 700; font-size: 13.5px; border-radius: 8px 8px 0 0;">
                            <i class="fa fa-th-large text-warning"></i> Menu Items / Products ({{ isset($produk) ? $produk->count() : 0 }})
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab 1: Raw Stock Materials -->
                    <div class="tab-pane active" id="tab-raw-materials">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-raw-material-picker" style="width: 100%;">
                                <thead style="background: #f8fafc;">
                                    <th width="5%">#</th>
                                    <th>Code</th>
                                    <th>Material / Ingredient Name</th>
                                    <th>Unit</th>
                                    <th>Current Stock</th>
                                    <th>Cost Price</th>
                                    <th width="10%"><i class="fa fa-cog"></i></th>
                                </thead>
                                <tbody>
                                    @if(isset($rawMaterials) && $rawMaterials->isNotEmpty())
                                        @foreach ($rawMaterials as $key => $mat)
                                            <tr>
                                                <td width="5%">{{ $key + 1 }}</td>
                                                <td><span class="label" style="background:#0284c7; color:#fff; font-size:11px; font-weight:700;">{{ $mat->kode_material }}</span></td>
                                                <td><strong style="color: #0f172a;">{{ $mat->nama_material }}</strong></td>
                                                <td><span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600;">{{ ucfirst($mat->satuan) }}</span></td>
                                                <td>
                                                    @if($mat->stok <= 0)
                                                        <span class="text-danger font-weight-bold">0 {{ $mat->satuan }}</span>
                                                    @else
                                                        <span class="text-success font-weight-bold">{{ $mat->stok }} {{ $mat->satuan }}</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ format_currency($mat->harga_beli) }}</strong></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm btn-flat" style="border-radius: 6px; font-weight: 700;"
                                                        onclick="pilihRawMaterial('{{ $mat->id }}', '{{ $mat->kode_material }}')">
                                                        <i class="fa fa-plus-circle"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Menu Items -->
                    <div class="tab-pane" id="tab-menu-products">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-produk-picker" style="width: 100%;">
                                <thead style="background: #f8fafc;">
                                    <th width="5%">#</th>
                                    <th>Code</th>
                                    <th>Product Name</th>
                                    <th>Current Stock</th>
                                    <th>Cost Price</th>
                                    <th width="10%"><i class="fa fa-cog"></i></th>
                                </thead>
                                <tbody>
                                    @if(isset($produk) && $produk->isNotEmpty())
                                        @foreach ($produk as $key => $item)
                                            <tr>
                                                <td width="5%">{{ $key + 1 }}</td>
                                                <td><span class="label label-success">{{ $item->kode_produk }}</span></td>
                                                <td><strong style="color: #0f172a;">{{ $item->nama_produk }}</strong></td>
                                                <td>{{ $item->stok }}</td>
                                                <td><strong>{{ format_currency($item->harga_beli) }}</strong></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm btn-flat" style="border-radius: 6px; font-weight: 700;"
                                                        onclick="pilihProduk('{{ $item->id_produk }}', '{{ $item->kode_produk }}')">
                                                        <i class="fa fa-plus-circle"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>