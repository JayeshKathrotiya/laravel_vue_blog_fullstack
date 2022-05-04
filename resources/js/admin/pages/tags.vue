<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="
            _1adminOverveiw_table_recent
            _box_shadow
            _border_radious
            _mar_b30
            _p20
          "
        >
          <p class="_title0">
            Tags
            <Button @click="addModal = true"
              ><Icon type="md-add" />Add Tag</Button
            >
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>Id</th>
                <th>Tag Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                <td>{{ tag.id }}</td>
                <td class="_table_name">
                  {{ tag.tagName }}
                </td>
                <td>{{ tag.created_at }}</td>
                <td>
                  <Button
                    type="info"
                    size="small"
                    @click="showEditModal(tag, i)"
                    >Edit</Button
                  >
                  <Button
                    type="error"
                    size="small"
                    @click="setDeleteModal(tag, i)"
                    :loading="tag.isDeleteing"
                    >Delete</Button
                  >
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <!-- tag adding modal -->
        <Modal
          v-model="addModal"
          title="Add Tag"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="data.tagName" placeholder="Add tag name." />

          <div slot="footer">
            <Button type="default" @click="addModal = false">Close</Button>
            <Button
              type="primary"
              @click="addTag"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding..." : "Add Tag" }}</Button
            >
          </div>
        </Modal>

        <!-- tag editing modal -->
        <Modal
          v-model="editModal"
          title="Edit Tag"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="editData.tagName" placeholder="Edit tag name." />

          <div slot="footer">
            <Button type="default" @click="editModal = false">Close</Button>
            <Button
              type="primary"
              @click="editTag"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Editing..." : "Edit Tag" }}</Button
            >
          </div>
        </Modal>

        <!-- tag deleting modal -->
        <Modal v-model="showDeleteModal" width="360">
          <p slot="header" style="color: #f60; text-align: center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
          </p>
          <div style="text-align: center">
            <p>Are you sure you want to delete this tag?</p>
          </div>
          <div slot="footer">
            <Button
              type="error"
              size="large"
              long
              :loading="isDeleteing"
              @click="deleteTag"
              >Delete</Button
            >
          </div>
        </Modal>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        tagName: "",
      },
      addModal: false,
      editData: {
        tagName: "",
        id: "",
      },
      editModal: false,
      deleteItem: {},
      showDeleteModal: false,
      isDeleteing: false,
      isAdding: false,
      tags: [],
      index: -1,
    };
  },
  methods: {
    async addTag() {
      if (this.data.tagName.trim() == "") {
        return this.error("Tag name is required");
      }
      const res = await this.callApi("post", "app/create_tag", this.data);
      if (res.status == 201) {
        this.tags.unshift(res.data);
        this.success("Tag added successfully");
        this.addModal = false;
        this.data.tagName = "";
      } else {
        if (res.status == 422) {
          this.error(res.data.errors.tagName[0]);
        } else {
          this.swr();
        }
      }
    },

    async editTag() {
      if (this.editData.tagName.trim() == "") {
        return this.error("Tag name is required");
      }
      const res = await this.callApi("post", "app/edit_tag", this.editData);
      if (res.status == 200) {
        this.tags[this.index] = res.data;
        this.success("Tag edited successfully");
        this.editModal = false;
        this.editData.tagName = "";
      } else {
        if (res.status == 422) {
          this.error(res.data.errors.tagName[0]);
        } else {
          this.swr();
        }
      }
      this.index = -1;
    },

    showEditModal(tag, i) {
      this.editData = {
        tagName: tag.tagName,
        id: tag.id,
      };
      this.index = i;
      this.editModal = true;
    },

    async deleteTag() {
      // tag.isDeleteing = true; <-- this won't work
      // this.$set(this.tag, "isDeleteing", true); // this will work because vue use $set for setting a new property which did not exist before in the object
      this.isDeleteing = true;
      const res = await this.callApi("post", "app/delete_tag", this.deleteItem);
      if (res.status == 200) {
        this.tags.splice(this.index, 1);
        this.success("Tag deleted successfully");
      } else {
        this.swr();
        // tag.isDeleteing = false;
      }
      this.isDeleteing = false;
      this.showDeleteModal = false;
    },

    setDeleteModal(tag, i) {
      this.deleteItem = tag;
      this.index = i;
      this.showDeleteModal = true;
    },

  },
  async created() {
    const res = await this.callApi("get", "app/get_tags");
    if (res.status == 200) {
      this.tags = res.data;
    } else {
      this.swr();
    }
  },
};
</script>