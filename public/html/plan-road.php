<div class="mt-5 road-section-">
                        <div class="form-group row">
                            <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span><span class="schedule">行程表<span></label>
                            <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-road-' value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この行程表を削除
                                    </div>
                            </div>
                        </div>
                       
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> タイトル</label>
                            <div class="col-md-6">
                                <input id="road_map_title" type="text" class="form-control" name="road_map_title" value="">
                            </div>
                           
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 利用宿泊施設</label>
                            <div class="col-md-6">
                                <input id="road_map_build" type="text" class="form-control" name="road_map_build" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">朝食</label>
                            <div class="col-md-6 d-flex align-items-center">
                            <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                <input id="road_eat1_1" type="radio"  name="road_eat1" value="1" >
                                <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                             </label>
                             <label class="custom-control custom-radio pl-2  m-0 font-weight-normal" role="button">
                                <input id="road_eat1_0" type="radio"  name="road_eat1" value="0" checked> 
                                <span class="custom-control-description">なし</span>
                            </label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">昼食</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_1" type="radio"  name="road_eat2" value="1" >
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                <input id="road_eat2_0" type="radio"  name="road_eat2" value="0"  checked>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                                
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 夕食</label>

                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                <input id="road_eat3_1" type="radio"  name="road_eat3" value="1" >
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                <input id="road_eat3_0" type="radio"  name="road_eat3" value="0"  checked>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"> 行程表</label>
                            <div class="col-md-6">
                                <textarea id="road_map" class="form-control"  name="road_map" rows="10"></textarea>
                            </div> 
                        </div>
                        </div>
