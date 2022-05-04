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
            Category
            <Button @click="addModal = true"
              ><Icon type="md-add" />Add Category</Button
            >
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>Id</th>
                <th>Icon image</th>
                <th>Category Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr
                v-for="(category, i) in categoryLists"
                :key="i"
                v-if="categoryLists.length"
              >
                <td>{{ category.id }}</td>
                <td class="table_image">
                  <img :src="category.iconImage" />
                </td>
                <td class="_table_name">
                  {{ category.categoryName }}
                </td>
                <td>{{ category.created_at }}</td>
                <td>
                  <Button
                    type="info"
                    size="small"
                    @click="showEditModal(category, i)"
                    >Edit</Button
                  >
                  <Button
                    type="error"
                    size="small"
                    @click="setDeleteModal(category, i)"
                    :loading="category.isDeleteing"
                    >Delete</Button
                  >
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <!-- category adding modal -->
        <Modal
          v-model="addModal"
          title="Add Category"
          :mask-closable="false"
          :closable="false"
        >
          <Input
            v-model="data.categoryName"
            placeholder="Add Category name."
            style="margin-bottom: 10px"
          />
          <div class="space"></div>
          <Upload
            ref="uploads"
            type="drag"
            action="/app/upload"
            :headers="{
              'x-csrf-token': token,
              'X-Requested-With': 'XMLHttpRequest',
            }"
            :on-success="handleSuccess"
            :format="['jpg', 'jpeg', 'png', 'gif', 'svg']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
            :on-error="handleError"
          >
            <div style="padding: 20px 0">
              <Icon
                type="ios-cloud-upload"
                size="52"
                style="color: #3399ff"
              ></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>
          <div v-if="data.iconImage" class="image_thumb">
            <div class="demo-upload-list">
              <img :src="`${data.iconImage}`" />
              <div class="demo-upload-list-cover">
                <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
              </div>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="addModal = false">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding..." : "Add Category" }}</Button
            >
          </div>
        </Modal>

        <!-- category editing modal -->
        <Modal
          v-model="editModal"
          title="Edit Category"
          :mask-closable="false"
          :closable="false"
        >
          <Input
            v-model="editData.categoryName"
            placeholder="Add Category name."
            style="margin-bottom: 10px"
          />
          <div class="space"></div>
          <Upload
            v-show="isIconImageNew"
            ref="editDataUploads"
            type="drag"
            action="/app/upload"
            :headers="{
              'x-csrf-token': token,
              'X-Requested-With': 'XMLHttpRequest',
            }"
            :on-success="handleSuccess"
            :format="['jpg', 'jpeg', 'png', 'gif', 'svg']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
            :on-error="handleError"
          >
            <div style="padding: 20px 0">
              <Icon
                type="ios-cloud-upload"
                size="52"
                style="color: #3399ff"
              ></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>
          <div v-if="editData.iconImage" class="image_thumb">
            <div class="demo-upload-list">
              <img :src="`${editData.iconImage}`" />
              <div class="demo-upload-list-cover">
                <Icon
                  type="ios-trash-outline"
                  @click="deleteImage(false)"
                ></Icon>
              </div>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeEditModal">Close</Button>
            <Button
              type="primary"
              @click="editCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Editing..." : "Edit Category" }}</Button
            >
          </div>
        </Modal>

        <!-- category deleting modal -->
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
        iconImage: "",
        categoryName: "",
      },
      addModal: false,
      editData: {
        iconImage: "",
        categoryName: "",
      },
      editModal: false,
      deleteItem: {},
      showDeleteModal: false,
      isDeleteing: false,
      isAdding: false,
      categoryLists: [],
      index: -1,
      token: "",
      isIconImageNew: false,
      isEditingItem: false,
    };
  },
  methods: {
    async addCategory() {
      if (this.data.categoryName.trim() == "") {
        return this.error("Category name is required");
      }
      if (this.data.iconImage.trim() == "") {
        return this.error("Icon image is required");
      }
      this.data.iconImage = `/uploads/${this.data.iconImage}`;
      const res = await this.callApi("post", "app/create_category", this.data);
      if (res.status == 201) {
        this.categoryLists.unshift(res.data);
        this.success("Category added successfully");
        this.addModal = false;
        this.data.categoryName = "";
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      } else {
        if (res.status == 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        } else {
          this.swr();
        }
      }
    },

    async editCategory() {
      if (this.editData.categoryName.trim() == "") {
        return this.error("Category name is required");
      }
      if (this.editData.iconImage.trim() == "") {
        return this.error("Icon image is required");
      }
      const res = await this.callApi(
        "post",
        "app/edit_category",
        this.editData
      );
      if (res.status == 200) {
        this.categoryLists[this.index] = res.data;
        this.success("Category edited successfully");
        this.editModal = false;
        this.editData.categoryName = "";
      } else {
        if (res.status == 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        } else {
          this.swr();
        }
      }
      this.index = -1;
    },

    showEditModal(category, i) {
      this.editData = {
        id: category.id,
        iconImage: category.iconImage,
        categoryName: category.categoryName,
      };
      this.index = i;
      this.editModal = true;
      this.isEditingItem = true;
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

    handleSuccess(res, file) {
      res = "/uploads/" + res;
      if (this.isEditingItem) {
        return (this.editData.iconImage = res);
      }
      this.data.iconImage = res;
    },
    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select correct image file format.",
      });
    },
    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M.",
      });
    },
    handleError(err, file) {
      this.$Notice.error({
        title: "Upload error",
        desc: file.errors.file.length
          ? file.errors.file[0]
          : "something went wrong!",
      });
    },
    async deleteImage(isAdd = true) {
        let image = isAdd ? this.data.iconImage : this.editData.iconImage;
      if (!isAdd) {
        // edit
        this.isIconImageNew = true;
        let image = this.editData.iconImage;
        this.editData.iconImage = "";
        this.$refs.editDataUploads.clearFiles();
      } else {
        let image = this.data.iconImage;
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      }
      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });
      if (res.status == 200) {
      } else {
        this.data.iconImage = image;
        this.swr();
      }
    },
    closeEditModal() {
      this.isEditingItem = false;
      this.editModal = false;
    },
  },
  async created() {
    this.token = window.Laravel.csrfToken;
    const res = await this.callApi("get", "app/get_category");
    if (res.status == 200) {
      this.categoryLists = res.data;
    } else {
      this.swr();
    }
  },
};
</script>

<style>
</style>