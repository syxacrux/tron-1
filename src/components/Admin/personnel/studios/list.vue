<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>工作室管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <router-link class="btn-link-large add-btn" to="add" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加工作室
      </router-link>
    </div>
    <el-table v-if="listShow" :data="tableData" style="width: 100%" @selection-change="handleSelectionChange">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="工作室名称" prop="name"></el-table-column>
      <el-table-column label="备注" prop="explain"></el-table-column>
      <!--<el-table-column label="状态" prop="status">-->
        <!--<template slot-scope="scope">-->
          <!--<div>-->
            <!--{{ scope.row.status | status }}-->
          <!--</div>-->
        <!--</template>-->
      <!--</el-table-column>-->
      <el-table-column label="操作" width="200" v-if="editShow || deleteShow">
        <template slot-scope="scope">
          <div>
  					<span v-if="editShow">
  						<router-link :to="{ name: 'studiosEdit', params: { id: scope.row.id }}">
                <el-button size="small" type="primary">编辑</el-button>
  						</router-link>
  					</span>
            <span v-if="deleteShow">
  						<el-button size="small" type="danger" @click="confirmDelete(scope.row)">删除</el-button>
  					</span>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="pos-rel p-t-20">
      <!--<btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup>-->
      <div class="block pages">
        <el-pagination
            @current-change="handleCurrentChange"
            layout="prev, pager, next"
            :page-size="limit"
            :current-page="currentPage"
            :total="dataCount">
        </el-pagination>
      </div>
    </div>
  </div>
</template>

<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        tableData: [],
        dataCount: null,
        currentPage: 1,
        keywords: '',
        limit: 10,
        multipleSelection: []
      }
    },
    methods: {
//      表格发生改变时触发
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
//      删除工作室执行方法
      confirmDelete(item) {
        this.$confirm('确认删除该工作室?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/studios/', item.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              setTimeout(() => {
                _g.shallowRefresh(this.$route.name)
              }, 1500)
            })
          })
        }).catch(() => {
          // handle error
        })
      },
//      切换页码
      handleCurrentChange(page) {
        this.getAllStudios(page)
      },
//      获取工作室列表
      getAllStudios(page) {
        this.loading = true
        const data = {
          params: {
            keywords: this.keywords,
            page: page,
            limit: this.limit
          }
        }
        this.apiGet('admin/studios', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.dataCount = data.dataCount
          })
        })
      },
//      获取关键字
      getKeywords() {
        let data = this.$route.query
        if (data) {
          if (data.keywords) {
            this.keywords = data.keywords
          } else {
            this.keywords = ''
          }
        }
      },
//      初始化工作室列表内容
      init() {
        this.getAllStudios(1)
      }
    },
    created() {
      this.init()
    },
    computed: {
//      工作室列表
      listShow() {
        return _g.getHasRule('studios-index')
      },
//      添加工作室按钮
      addShow() {
        return _g.getHasRule('studios-save')
      },
//      编辑工作室按钮
      editShow() {
        return _g.getHasRule('studios-update')
      },
//      删除工作室按钮
      deleteShow() {
        return _g.getHasRule('studios-delete')
      }
    },
    components: {
      btnGroup
    },
    mixins: [http]
  }
</script>