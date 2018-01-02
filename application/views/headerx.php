   <?php if ($this->ion_auth->logged_in()): ?>

 <div class="top-navigation">



                <nav class="navbar navbar-static-top" role="navigation">



                    <div class="navbar-header">

                        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">

                            <i class="fa fa-reorder"></i>

                        </button> 

                        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-minimalize minimalize-styl-2 btn btn-success" type="button">

                            <i class="fa fa-reorder"></i>

                        </button>

<!--                     <a class="navbar-minimalize minimalize-styl-2 btn btn-success " href="#"><i class="fa fa-bars"></i> </a>

 -->

                    </div>

                    <div style="height: 1px;" aria-expanded="false" class="navbar-collapse collapse" id="navbar">

                     



                        <ul class="nav navbar-nav">   <?php if($this->ion_auth->in_group(array(1,2,3))): ?>

                                <li class="dropdown"> 
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Persediaan <span class="caret"></span></a>

                                        <!-- <a data-module="fin" href="<?php //cho domain() ?>fin">Keuangan</a>  -->
                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="inv" href="<?php echo domain() ?>inv">Dashboard Persediaan</a> </li>
                                                                        <li><a href="/inv/kartustok/">Kartu Stok</a></li>
                                                                        <li><a href="/inv/kartustok/laporan/">Laporan Stok</a></li>
                                                                        <li><a href="/inv/penyesuaian/">Penyesuaian Barang</a></li>

                                                                        

                                                                    </ul>
                                </li>
                                    <li class="dropdown">

                                                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Jual Beli <span class="caret"></span></a>

                                                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="pos" href="<?php echo domain() ?>pos">Dashboard Jual/Beli</a> </li>
                                                                        <li><a href="/pos/purchase_order/">PO</a></li>
                                                                        <li><a href="/pos/purchase_transaction/">Pembelian</a></li>
                                                                        <li><a href="/pos/sales_trx/">Penjualan</a></li>
                                                                        <li><a href="/acc/kartuhutang/">Kartu Hutang</a></li>
                                                                        <li><a href="/acc/kartupiutang/">Kartu Piutang</a></li>

                                                                        

                                                                    </ul>

                                                                </li>
                                                                <!-- <li> <a data-module="fin" href="<?php //echo domain() ?>fin">Keuangan</a> </li> -->
                                <li class="dropdown"> 
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Keuangan <span class="caret"></span></a>

                                        <!-- <a data-module="fin" href="<?php //cho domain() ?>fin">Keuangan</a>  -->
                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="fin" href="<?php echo domain() ?>fin">Dashboard Keuangan</a> </li>
                                                                        <li><a href="/fin/kas_masuk">Kas Masuk</a></li>
                                                                        <li><a href="/fin/kas_keluar">Kas Keluar</a></li>
                                                                        <li><a href="/fin/bank/masuk">Bank Masuk</a></li>
                                                                        <li><a href="/fin/bank/keluar">Bank Keluar</a></li>
                                                                        <li><a href="/fin/banks">Rekening Kas/Bank</a></li>
                                                                                                                               

                                                                    </ul>
                                </li>
                                <li class="dropdown"> 
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Akuntansi <span class="caret"></span></a>

                                        <!-- <a data-module="fin" href="<?php //cho domain() ?>fin">Keuangan</a>  -->
                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="acc" href="<?php echo domain() ?>acc">Dashboard Akuntansi</a> </li>
                                                                        <li><a href="/acc/rekening/data">Rekening Perkiraan</a></li>
                                                                        <li><a href="/acc/jurnal">Jurnal</a></li>
                                                                        <li><a href="/acc/saldorekening/">Saldo Rekening</a></li>
                                                                        <li><a href="/acc/kartuhutang/">Hutang Dagang</a></li>
                                                                        <li><a href="/acc/kartuhutang/laporan">Laporan Hutang Dagang</a></li>
                                                                        <li><a href="/acc/kartupiutang/">Piutang Dagang</a></li>
                                                                        <li><a href="/acc/kartupiutang/laporan">Laporan Piutang Dagang</a></li>

                                                                        

                                                                    </ul>
                                </li>

                                

                                <!-- <li> <a data-module="acc" href="<?php //echo domain() ?>pay">Kepegawaian</a> </li> -->

                                <!-- <li> <a data-module="farm" href="<?php //echo domain() ?>farm">Peternakan</a> </li> -->
                                 <?php endif; ?>
                                 <?php if($this->ion_auth->in_group(array(1,2,15))): ?>
                                <li class="dropdown"> 
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Peternakan <span class="caret"></span></a>

                                        <!-- <a data-module="fin" href="<?php //cho domain() ?>fin">Keuangan</a>  -->
                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="farm" href="<?php echo domain() ?>farm">Dashboard Peternakan</a> </li>
                                                                        <li><a href="/farm/recording_ayam">Rekam Ayam</a></li>
                                                                        <li><a href="/farm/recording_telur">Rekam Telur</a></li>
                                                                        <li><a href="/farm/recording_pakan/">Rekam Pakan</a></li>
                                                                        <li><a href="/farm/assembly_pakan/">Campur Pakan</a></li>
                                                                        <li><a href="/farm/laporan/laporan_baru">Laporan Baru</a></li>
                                                                     

                                                                    </ul>
                                </li>
                                <?php endif; ?> 
                                <?php if($this->ion_auth->in_group(array(1))): ?>
                                <li class="dropdown"> 
                                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Administrator<span class="caret"></span></a>

                                        <!-- <a data-module="fin" href="<?php //cho domain() ?>fin">Keuangan</a>  -->
                                    <ul role="menu" class="dropdown-menu">

                                                                        <li> <a data-module="farm" href="<?php echo domain() ?>farm">Dashboard Administrator</a> </li>
                                                                        <li><a href="/admin/konversi">Desktop <i class="fa fa-arrow-right"></i> Web</a></li>
                                                                        <li><a href="/admin/backuprestore">Backup/Restore Database</a></li>
                                                                        <li><a href="/admin/exporimpor">Export/Import</a></li>
                                                                        <li><a href="/admin/efaktur">E-Faktur</a></li>
                                                                        <li><a href="/admin/setup">Setting Up</a></li>
                                                                        <li><a href="/admin/log/server">Log Server</a></li>
                                                                        <li><a href="/admin/log/aplikasi">Log Aplikasi</a></li>
                                                                        <li><a href="/admin/log/user">Log User</a></li>
                                                                     

                                                                    </ul>
                                </li>
                                <?php endif; ?>

                           

                        </ul>

                        

                        <ul class="nav navbar-top-links navbar-right">

                            <?php if ($this->ion_auth->logged_in()):?> 

                                <li>

                                    <a href="<?php echo base_url('auth/logout') ?>">

                                        <i class="fa fa-sign-out"></i> Log out

                                    </a>

                                </li>

                            <?php elseif(!$this->ion_auth->logged_in()): ?>

                                <li>

                                    <a href="<?php echo base_url('auth/login') ?>">

                                        <i class="fa fa-sign-in"></i> Login

                                    </a>

                                </li>

                            <?php endif; ?> 

                        </ul>

                    </div>

                </nav>

        

            </div>
            <?php endif; ?>