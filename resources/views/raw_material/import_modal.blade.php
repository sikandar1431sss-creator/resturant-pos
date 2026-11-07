<!-- Simple 1-Click CSV Import Modal for Raw Materials -->
<div class="modal fade" id="modal-import-raw-csv" tabindex="-1" role="dialog" aria-labelledby="modal-import-raw-csv" data-backdrop="static">
    <div class="modal-dialog" role="document" style="max-width: 600px; width: 95%;">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.25); border: none;">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); color: white; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.85; font-size: 24px; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0; font-size: 18px; color: #ffffff;">
                    <i class="fa fa-file-excel-o" style="font-size: 22px;"></i>
                    <span>Import Raw Materials from CSV / Excel</span>
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 24px; background: #f8fafc;">
                <form id="form-raw-csv-import" enctype="multipart/form-data">
                    @csrf

                    <!-- Template Download helper banner -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <div>
                            <strong style="color: #1e293b; font-size: 13px; display: block;">Standard Excel / CSV Template</strong>
                            <small style="color: #64748b;">Naya format banana chahein to sample sheet download karein</small>
                        </div>
                        <a href="{{ route('raw_material.sample_csv') }}" class="btn btn-default btn-flat" style="border-radius: 6px; font-weight: 600; font-size: 12px; border-color: #cbd5e1; background: #f8fafc;">
                            <i class="fa fa-download text-primary"></i> Download Sample CSV
                        </a>
                    </div>

                    <!-- Drag & Drop / File Select Box -->
                    <label for="raw_csv_file_input" id="raw-drop-zone" style="display: block; background: #ffffff; border: 2px dashed #ea580c; border-radius: 12px; padding: 32px 20px; text-align: center; cursor: pointer; transition: all 0.25s ease-in-out; margin-bottom: 20px; position: relative;">
                        <i class="fa fa-cloud-upload" style="font-size: 48px; color: #ea580c; margin-bottom: 10px; display: block;"></i>
                        <h4 style="margin: 0 0 5px 0; font-weight: 700; color: #1e293b; font-size: 16px;">Click to Select or Drop CSV File</h4>
                        <p style="color: #64748b; font-size: 13px; margin-bottom: 14px;">Kisi bhi software ya Excel ki CSV file select karein</p>
                        
                        <span class="btn btn-warning btn-flat" style="border-radius: 6px; font-weight: 600; padding: 8px 22px; font-size: 13px; pointer-events: none; background: #ea580c; border-color: #c2410c; color: #fff;">
                            <i class="fa fa-folder-open-o"></i> Browse CSV File
                        </span>

                        <input type="file" name="csv_file" id="raw_csv_file_input" accept=".csv, .txt, text/csv, text/plain, application/vnd.ms-excel" onchange="handleRawFileChosen(this.files[0])" style="position: absolute; left: -9999px; top: -9999px; opacity: 0; width: 1px; height: 1px;">

                        <!-- File Selected Notification -->
                        <div id="raw-selected-file-info" style="display: none; margin-top: 16px; font-weight: 600; color: #047857; font-size: 14px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 10px 14px;">
                            <i class="fa fa-check-circle" style="font-size: 18px; vertical-align: middle;"></i> 
                            <span id="raw-selected-filename" style="margin-left: 6px;"></span>
                        </div>
                    </label>

                    <!-- Default Unit of Measure Selection -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                        <label for="raw_default_satuan" style="font-size: 13px; color: #334155; font-weight: 600; margin-bottom: 6px; display: block;">
                            Default Unit of Measure <span style="font-weight: normal; color: #64748b;">(Agar CSV mein unit/satuan na ho to is unit mein add hoga):</span>
                        </label>
                        <select name="default_satuan" id="raw_default_satuan" class="form-control" style="border-radius: 6px; height: 40px; font-size: 13px;">
                            @if(isset($units) && $units->isNotEmpty())
                                @foreach($units as $u)
                                    <option value="{{ $u->nama_satuan }}" {{ in_array(strtolower($u->nama_satuan), ['piece', 'pcs', 'kg']) ? 'selected' : '' }}>
                                        {{ ucfirst($u->nama_satuan) }} ({{ $u->simbol ?: $u->nama_satuan }}) - {{ $u->deskripsi }}
                                    </option>
                                @endforeach
                            @else
                                <option value="kg">Kilogram (kg)</option>
                                <option value="gram">Gram (g)</option>
                                <option value="liter">Liter (L)</option>
                                <option value="ml">Milliliter (ml)</option>
                                <option value="piece" selected>Piece (pcs)</option>
                                <option value="box">Box (box)</option>
                                <option value="pack">Packet (pack)</option>
                                <option value="can">Can (can)</option>
                                <option value="bottle">Bottle (btl)</option>
                                <option value="dozen">Dozen (doz)</option>
                            @endif
                        </select>
                    </div>

                    <!-- Progress / Status Spinner (Hidden initially) -->
                    <div id="raw-import-loading" style="display: none; text-align: center; padding: 15px; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; margin-bottom: 15px;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #ea580c; vertical-align: middle;"></i>
                        <span style="font-weight: 600; color: #9a3412; margin-left: 10px; font-size: 14px;">Processing file &amp; updating raw stock inventory...</span>
                    </div>

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 500;">
                    <i class="fa fa-times"></i> Cancel
                </button>

                <button type="button" id="btn-do-raw-import" class="btn btn-success btn-flat" style="border-radius: 6px; font-weight: 700; padding: 9px 24px; font-size: 14px; background: #ea580c; border-color: #c2410c;" onclick="doDirectRawImport()">
                    <i class="fa fa-cloud-upload"></i> Upload &amp; Import Materials
                </button>
            </div>

        </div>
    </div>
</div>

