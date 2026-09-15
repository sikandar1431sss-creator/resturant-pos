<!-- DEAL DETAILS MODAL -->
<div class="modal fade" id="modal-deal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-deal-detail">
    <div class="modal-dialog modal-md" role="document" style="max-width: 580px;">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.2);">
            <!-- Modal Header -->
            <div class="modal-header" style="background: #ffffff; padding: 16px 22px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <h4 class="modal-title" style="font-weight: 800; font-size: 16px; color: #0f172a; margin: 0;">Deal Breakdown</h4>
                    <span id="detailDealCode" style="font-size: 11.5px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 4px; padding: 2px 7px; font-family: monospace;">--</span>
                </div>
                <button type="button" class="close" data-dismiss="modal" style="color: #64748b; opacity: 0.8; font-size: 24px; margin: 0; line-height: 1; outline: none; border: none; background: none;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 22px; background: #f8fafc;">
                
                <!-- Deal Title & Description Box -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                        <div>
                            <h3 id="detailDealTitle" style="font-weight: 800; font-size: 17px; color: #0f172a; margin: 0 0 4px 0;">--</h3>
                            <p id="detailDealDesc" style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.4;">--</p>
                        </div>
                        <span id="detailDealStatusBadge" class="badge" style="padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 11px;">Active</span>
                    </div>
                </div>

                <!-- 3 Metric Cards: Selling, Cost, Profit -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px;">
                    <!-- Selling Price Card -->
                    <div style="background: #ffffff; border: 1.5px solid #ffedd5; border-radius: 12px; padding: 12px 14px; box-shadow: 0 2px 4px rgba(249, 115, 22, 0.04);">
                        <div style="font-size: 11px; font-weight: 700; color: #9a3412; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                            <i class="fa fa-tag" style="color: #ea580c;"></i> Selling
                        </div>
                        <div id="detailDealPrice" style="font-size: 16px; font-weight: 900; color: #c2410c;">0</div>
                    </div>

                    <!-- Cost Price Card -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                            <i class="fa fa-calculator" style="color: #64748b;"></i> Cost
                        </div>
                        <div id="detailDealCost" style="font-size: 16px; font-weight: 800; color: #334155;">0</div>
                    </div>

                    <!-- Profit Card -->
                    <div style="background: #ffffff; border: 1.5px solid #dcfce7; border-radius: 12px; padding: 12px 14px; box-shadow: 0 2px 4px rgba(34, 197, 94, 0.05);">
                        <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                            <i class="fa fa-line-chart" style="color: #16a34a;"></i> Profit
                        </div>
                        <div id="detailDealProfit" style="font-size: 16px; font-weight: 900; color: #15803d;">0</div>
                    </div>
                </div>

                <!-- Included Items Section -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <div style="padding: 12px 16px; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h5 style="font-weight: 800; font-size: 13.5px; color: #0f172a; margin: 0;">
                            Items
                        </h5>
                        <span id="detailItemsCountBadge" class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700; font-size: 11px; border: 1px solid #cbd5e1;">0 Items</span>
                    </div>

                    <div class="table-responsive" style="margin: 0;">
                        <table class="table" style="margin-bottom: 0; font-size: 13px;">
                            <thead>
                                <tr style="background: #f8fafc; color: #64748b; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.4px;">
                                    <th width="8%" style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; border-top: none;">#</th>
                                    <th style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; border-top: none;">Menu Dish</th>
                                    <th width="15%" class="text-center" style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; border-top: none;">Qty</th>
                                    <th width="25%" class="text-right" style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; border-top: none;">Cost Price</th>
                                </tr>
                            </thead>
                            <tbody id="detailDealItemsList">
                                <!-- Injected by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 22px; display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 8px; font-weight: 700; padding: 8px 22px; border: 1px solid #cbd5e1; color: #475569;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

